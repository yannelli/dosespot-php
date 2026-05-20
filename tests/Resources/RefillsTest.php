<?php

declare(strict_types=1);

it('lists clinician refills, with and without onBehalfOf', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();

    $client->refills()->forClinician();
    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/clinician');

    $client->refills()->forClinician(onBehalfOf: 77);
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/clinician/77');
});

it('approves and denies refills', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->refills()->approve(123);
    $client->refills()->deny(456, ['DenialReason' => 'BadDrug']);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/123/approve');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/456/deny');
    expect(json_decode((string) $factory->history[1]['request']->getBody(), true))
        ->toBe(['DenialReason' => 'BadDrug']);
});
