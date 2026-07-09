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

it('marks a prescription as printed, with and without a pin', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();

    $client->prescriptions()->setPrinted(5, 99);
    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/setPrinted');

    $client->prescriptions()->setPrinted(5, 99, pin: '654321');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/setPrinted/654321');
});

it('marks multiple prescriptions as ready to sign', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->readyToSign(5, [10, 20, 30]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/readyToSign');
    expect(json_decode((string) $request->getBody(), true))
        ->toBe(['PrescriptionIds' => [10, 20, 30]]);
});

it('bulk-deletes prescriptions via DELETE with a JSON body', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->destroyBulk(5, [1, 2]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('DELETE');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/delete');
    expect(json_decode((string) $request->getBody(), true))
        ->toBe(['PrescriptionIds' => [1, 2]]);
});

it('retrieves a prescription by id', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 99]);

    $factory->preauthorizedClient()->prescriptions()->find(5, 99);

    expect($factory->lastRequest()->getMethod())->toBe('GET');
    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99');
});

it('retrieves EPCS suggestions for a prescription', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->prescriptions()->epcsSuggestions(5, 99);

    expect($factory->lastRequest()->getMethod())->toBe('GET');
    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/epcsSuggestions');
});

it('retrieves the prescription log', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->prescriptions()->log(5, 99);

    expect($factory->lastRequest()->getMethod())->toBe('GET');
    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/log');
});

it('updates a coded prescription', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 99]);

    $factory->preauthorizedClient()->prescriptions()->updateCoded(5, 99, ['Quantity' => 60]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/coded/99');
    expect(json_decode((string) $request->getBody(), true))->toBe(['Quantity' => 60]);
});

it('creates an NDC prescription', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 100]);

    $factory->preauthorizedClient()->prescriptions()->createNdc(5, ['NDC' => '12345-678-90']);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/ndc');
});

it('copies a prescription', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 200]);

    $factory->preauthorizedClient()->prescriptions()->copy(5, 99);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/copy');
});

it('ignores a prescription error', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->ignoreError(5, 99);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/ignoreError');
});

it('updates a prescription status', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->updateStatus(5, 99, ['Status' => 2]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/updateStatus');
    expect(json_decode((string) $request->getBody(), true))->toBe(['Status' => 2]);
});

it('sends an EPCS prescription', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->sendEpcs(5, 99, ['TransactionId' => 'abc']);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/sendEpcs');
    expect(json_decode((string) $request->getBody(), true))->toBe(['TransactionId' => 'abc']);
});

it('cancels a prescription on behalf of another clinician', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->prescriptions()->cancelOnBehalfOf(5, 99, 77, ['Reason' => 'wrong']);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/prescriptions/99/cancelOnBehalfOf/77');
});
