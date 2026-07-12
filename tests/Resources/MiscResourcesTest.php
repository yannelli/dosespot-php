<?php

declare(strict_types=1);

it('calls the general health check', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 1, 'Result' => ['ResultCode' => 'OK']]);

    $factory->preauthorizedClient()->general()->check();

    expect($factory->lastRequest()->getUri()->getPath())->toBe('/webapi/api/general/check');
});

it('searches compounds', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->compounds()->search(name: 'cream', ndc: '12345');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('name=cream');
    expect($uri)->toContain('ndc=12345');
});

it('searches diagnoses by ICD and CDT', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->diagnosis()->searchByIcd('headache');
    $client->diagnosis()->searchByCdt('cleaning');

    expect((string) $factory->history[0]['request']->getUri())
        ->toContain('/api/diagnosis/searchByICD?searchString=headache');
    expect((string) $factory->history[1]['request']->getUri())
        ->toContain('/api/diagnosis/searchByCDT?searchString=cleaning');
});

it('lists and finds dispense units', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->dispenseUnits()->all();
    $client->dispenseUnits()->find(26);

    expect($factory->history[0]['request']->getUri()->getPath())->toBe('/webapi/api/dispenseUnits');
    expect($factory->history[1]['request']->getUri()->getPath())->toBe('/webapi/api/units/dispenseUnits/26');
});

it('initiates a drug-database migration', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->migration()->initiateDrugDb(clientId: 99);

    expect($factory->lastRequest()->getMethod())->toBe('POST');
    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/client/99/initiateDrugDbMigration');
});
