<?php

declare(strict_types=1);

it('routes the four list endpoints', function () {
    $factory = factory();
    foreach (range(1, 4) as $_) {
        $factory->pushResponse(200, []);
    }

    $client = $factory->preauthorizedClient();
    $client->rxChange()->forClinician();
    $client->rxChange()->forClinic();
    $client->rxChange()->forClient();
    $client->rxChange()->forPatient(5);

    $paths = array_map(
        fn ($entry) => $entry['request']->getUri()->getPath(),
        $factory->history,
    );

    expect($paths)->toBe([
        '/webapi/api/notifications/rxchange/clinician',
        '/webapi/api/notifications/rxchange/clinic',
        '/webapi/api/notifications/rxchange/client',
        '/webapi/api/notifications/rxchange/patients/5',
    ]);
});

it('approves and denies rxchange notifications', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->rxChange()->approve(123, ['Note' => 'OK']);
    $client->rxChange()->deny(456, ['Reason' => 'WrongPatient']);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/rxchange/123/approve');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/rxchange/456/deny');
});

it('reconciles on behalf of another clinician', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->rxChange()->reconcileOnBehalfOf(
        patientId: 5,
        rxChangeId: 17,
        onBehalfOf: 77,
    );

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/notifications/rxchange/patients/5/17/reconcileOnBehalfOf/77');
});

it('reconciles an rxchange notification', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->rxChange()->reconcile(
        patientId: 5,
        rxChangeId: 17,
        payload: ['Note' => 'Matched'],
    );

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/notifications/rxchange/patients/5/17/reconcile');
    expect(json_decode((string) $request->getBody(), true))->toBe(['Note' => 'Matched']);
});

it('changes patient for an rxchange notification, with and without onBehalfOf', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->rxChange()->changePatient(17, ['PatientId' => 99]);
    $client->rxChange()->changePatientOnBehalfOf(rxChangeId: 17, patientId: 99, onBehalfOf: 77);

    expect($factory->history[0]['request']->getMethod())->toBe('POST');
    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/rxchange/17/changePatient');
    expect(json_decode((string) $factory->history[0]['request']->getBody(), true))
        ->toBe(['PatientId' => 99]);
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/rxchange/17/changePatientOnBehalfOf/99/77');
});

it('approves and denies on behalf of another clinician', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->rxChange()->approveOnBehalfOf(123, 77, ['Note' => 'OK']);
    $client->rxChange()->denyOnBehalfOf(456, 77, ['Reason' => 'WrongPatient']);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/rxchange/123/approveOnBehalfOf/77');
    expect(json_decode((string) $factory->history[0]['request']->getBody(), true))
        ->toBe(['Note' => 'OK']);
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/notifications/rxchange/456/denyOnBehalfOf/77');
    expect(json_decode((string) $factory->history[1]['request']->getBody(), true))
        ->toBe(['Reason' => 'WrongPatient']);
});
