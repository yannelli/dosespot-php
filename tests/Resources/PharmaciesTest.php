<?php

declare(strict_types=1);

it('serializes specialty lists with array-style keys', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->pharmacies()->search(
        city: 'Austin',
        state: 'TX',
        specialty: [1, 2],
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('specialty%5B0%5D=1');
    expect($uri)->toContain('specialty%5B1%5D=2');
    expect($uri)->toContain('city=Austin');
});

it('retrieves a pharmacy by id', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 42, 'Name' => 'CVS']);

    $factory->preauthorizedClient()->pharmacies()->find(42);

    expect((string) $factory->lastRequest()->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/pharmacies/42');
});

it('adds and removes a pharmacy from a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();

    $client->pharmacies()->addToPatient(5, 42);
    expect($factory->history[0]['request']->getMethod())->toBe('POST');
    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/pharmacies/42');

    $client->pharmacies()->removeFromPatient(5, 42);
    expect($factory->history[1]['request']->getMethod())->toBe('DELETE');
});
