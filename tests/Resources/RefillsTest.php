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

it('lists clinic refills, with and without onBehalfOf', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();

    $client->refills()->forClinic();
    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/clinic');

    $client->refills()->forClinic(onBehalfOf: 88);
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/clinic/88');
});

it('lists patient refills, with and without onBehalfOf', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();

    $client->refills()->forPatient(5);
    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/patients/5');

    $client->refills()->forPatient(5, onBehalfOf: 77);
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/patients/5/77');
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

it('approves and denies on behalf of another clinician', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->refills()->approveOnBehalfOf(123, 77);
    $client->refills()->denyOnBehalfOf(456, 77, ['DenialReason' => 'TooSoon']);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/123/approveOnBehalfOf/77');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/456/denyOnBehalfOf/77');
    expect(json_decode((string) $factory->history[1]['request']->getBody(), true))
        ->toBe(['DenialReason' => 'TooSoon']);
});

it('changes patient for a refill', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->refills()->changePatient(123, 99);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/123/changePatient');
    expect($request->getUri()->getQuery())->toContain('patientId=99');
});

it('changes patient on behalf of another clinician', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->refills()
        ->changePatientOnBehalfOf(refillId: 123, onBehalfOf: 77, patientId: 99);

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/123/changePatientOnBehalfOf/77/99');
});

it('replaces a refill', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->refills()->replace(123, ['NewPrescriptionId' => 555]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/notifications/refills/123/replace');
    expect(json_decode((string) $request->getBody(), true))
        ->toBe(['NewPrescriptionId' => 555]);
});
