<?php

declare(strict_types=1);

it('creates, retrieves, patches, and replaces a clinic', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 7]);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->clinics()->create(['Name' => 'Main']);
    $client->clinics()->find(7);
    $client->clinics()->update(7, ['Name' => 'Renamed']);
    $client->clinics()->replace(7, ['Name' => 'Replaced', 'Address1' => '1 Way']);

    $methods = array_map(fn ($entry) => $entry['request']->getMethod(), $factory->history);
    expect($methods)->toBe(['POST', 'GET', 'POST', 'PUT']);
});

it('removes clinicians from a clinic via query parameter', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->clinics()->removeClinicians(
        clinicId: 7,
        payload: ['ClinicianIds' => [1, 2]],
    );

    $uri = $factory->lastRequest()->getUri();
    expect($uri->getPath())->toBe('/webapi/api/clinics/clinicRemoveClinicians');
    expect($uri->getQuery())->toContain('clinicId=7');
});

it('creates a clinic group', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->clinics()->createGroup(['Name' => 'North']);

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/clinics/clinicGroup');
});
