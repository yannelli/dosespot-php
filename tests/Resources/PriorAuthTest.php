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
