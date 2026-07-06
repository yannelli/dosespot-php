<?php

declare(strict_types=1);

it('runs through the prior-auth flow', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 100]);
    $factory->pushResponse(200, ['Question' => 'allergy?']);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();

    $client->priorAuth()->initiate(['PatientId' => 5, 'NDC' => '12345']);
    $client->priorAuth()->question(100, 1);
    $client->priorAuth()->answer(100, 1, ['AnswerText' => 'none']);
    $client->priorAuth()->submit(100);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/initiate');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/100/questions/1');
    expect($factory->history[2]['request']->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/100/answer/1');
    expect($factory->history[3]['request']->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/100/submit');
});

it('deletes a prior-auth attachment', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->priorAuth()->deleteAttachment(100, 5);

    expect($factory->lastRequest()->getMethod())->toBe('DELETE');
    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/100/attachment/5/delete');
});

it('finds, gets history, and lists prior-auths for a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 100]);
    $factory->pushResponse(200, ['Items' => []]);
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();
    $client->priorAuth()->find(100);
    $client->priorAuth()->history(100);
    $client->priorAuth()->forPatient(5);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/100');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/100/history');
    expect($factory->history[2]['request']->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/patients/5');
});

it('attaches and downloads an attachment', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 200]);
    $factory->pushResponse(200, ['Content' => 'base64data']);

    $client = $factory->preauthorizedClient();
    $client->priorAuth()->attach(100, ['FileName' => 'doc.pdf']);
    $client->priorAuth()->attachment(100, 200);

    expect($factory->history[0]['request']->getMethod())->toBe('POST');
    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/100/attach');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/priorAuth/100/attachment/200');
});

it('appeals, approves offline, denies offline, removes, and cancels', function () {
    $factory = factory();
    for ($i = 0; $i < 5; $i++) {
        $factory->pushResponse(200, []);
    }

    $client = $factory->preauthorizedClient();
    $client->priorAuth()->appeal(100, ['Reason' => 'need retry']);
    $client->priorAuth()->approveOffline(100);
    $client->priorAuth()->denyOffline(100, ['Reason' => 'not covered']);
    $client->priorAuth()->remove(100);
    $client->priorAuth()->cancel(100, ['Reason' => 'duplicate']);

    $paths = array_map(
        fn ($entry) => $entry['request']->getUri()->getPath(),
        $factory->history,
    );

    expect($paths)->toBe([
        '/webapi/api/priorAuth/100/appeal',
        '/webapi/api/priorAuth/100/approveOffline',
        '/webapi/api/priorAuth/100/denyOffline',
        '/webapi/api/priorAuth/100/remove',
        '/webapi/api/priorAuth/100/cancel',
    ]);

    $methods = array_map(
        fn ($entry) => $entry['request']->getMethod(),
        $factory->history,
    );
    expect($methods)->toBe(['POST', 'POST', 'POST', 'POST', 'POST']);
});
