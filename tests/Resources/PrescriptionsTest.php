<?php

declare(strict_types=1);

it('creates a coded prescription', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 99]);

    $factory->preauthorizedClient()->prescriptions()->createCoded(5, [
        'PharmacyId' => 1,
        'DispensableDrugId' => 1234,
    ]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())->toBe('/webapi/api/patients/5/prescriptions/coded');
});

it('sends a prescription with a PIN', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->send(5, 99, pin: '123456');

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/send/123456');
});

it('sends prescriptions in bulk on behalf of another clinician', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()
        ->sendBulkOnBehalfOf(5, 77, [1, 2, 3]);

    $request = $factory->lastRequest();
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/sendOnBehalfOf/77');
    expect(json_decode((string) $request->getBody(), true))->toBe(['PrescriptionIds' => [1, 2, 3]]);
});

it('cancels a prescription', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->cancel(5, 99);

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/cancel');
});

it('destroys a prescription with DELETE', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->destroy(5, 99);

    expect($factory->lastRequest()->getMethod())->toBe('DELETE');
    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/delete');
});

it('changes pharmacy via query string', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->changePharmacy(5, 99, 42);

    $uri = $factory->lastRequest()->getUri();
    expect($uri->getPath())->toBe('/webapi/api/patients/5/prescriptions/99/changePharmacy');
    expect($uri->getQuery())->toContain('pharmacyId=42');
});
