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

it('searches pharmacies with optional filters and omits null query params', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();

    $client->pharmacies()->search(
        name: 'CVS',
        city: 'Austin',
        state: 'TX',
        zip: '78701',
        address: '1 Congress Ave',
        phoneOrFax: '5125550100',
        ncpdpID: '1234567',
    );

    $uri = (string) $factory->history[0]['request']->getUri();
    expect($uri)->toContain('name=CVS')
        ->and($uri)->toContain('city=Austin')
        ->and($uri)->toContain('state=TX')
        ->and($uri)->toContain('zip=78701')
        ->and($uri)->toContain('address=1%20Congress%20Ave')
        ->and($uri)->toContain('phoneOrFax=5125550100')
        ->and($uri)->toContain('ncpdpID=1234567')
        ->and($uri)->not->toContain('specialty');

    $client->pharmacies()->search();
    expect((string) $factory->history[1]['request']->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/pharmacies/search');
});
