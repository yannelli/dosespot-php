<?php

declare(strict_types=1);

it('searches the allergen database', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->allergies()->search('penicillin');

    expect((string) $factory->lastRequest()->getUri())
        ->toContain('/api/allergies/search?q=penicillin');
});

it('records, replaces, updates, and lists allergies for a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 1]);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, ['Items' => []]);

    $allergies = $factory->preauthorizedClient()->allergies();

    $allergies->create(5, ['Name' => 'Penicillin']);
    $allergies->replace(5, 17, ['Name' => 'Penicillin (replaced)']);
    $allergies->update(5, 17, ['Severity' => 'Severe']);
    $allergies->forPatient(5);

    $methods = array_map(fn ($entry) => $entry['request']->getMethod(), $factory->history);
    expect($methods)->toBe(['POST', 'PUT', 'POST', 'GET']);

    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/allergies/17');
});

it('checks allergy-drug interactions for a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->allergies()->interactions(5);

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/allergies/interactions');
});
