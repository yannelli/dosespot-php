<?php

declare(strict_types=1);

it('creates a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 123, 'Result' => ['ResultCode' => 'OK']]);

    $response = $factory->preauthorizedClient()->patients()->create([
        'FirstName' => 'Jane',
        'LastName' => 'Doe',
    ]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect((string) $request->getUri())->toBe('https://my.staging.dosespot.com/webapi/api/patients');
    expect(json_decode((string) $request->getBody(), true))->toBe([
        'FirstName' => 'Jane',
        'LastName' => 'Doe',
    ]);
    expect($response)->toBe(['Id' => 123, 'Result' => ['ResultCode' => 'OK']]);
});

it('retrieves a patient by id', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 5]);

    $factory->preauthorizedClient()->patients()->find(5);

    expect((string) $factory->lastRequest()->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/patients/5');
});

it('lists patient prescriptions with optional date filters', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->patients()->prescriptions(
        patientId: 5,
        startDate: '2026-01-01',
        endDate: '2026-02-01',
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/patients/5/prescriptions');
    expect($uri)->toContain('startDate=2026-01-01');
    expect($uri)->toContain('endDate=2026-02-01');
});

it('merges patient records', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->patients()->merge([
        'KeepPatientId' => 1,
        'MergePatientIds' => [2, 3],
    ]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())->toBe('/webapi/api/patients/merge');
});

it('searches patients by name and dob', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->patients()->search(
        firstName: 'Jane',
        lastName: 'Doe',
        dob: new DateTimeImmutable('1990-01-02'),
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/patients/search');
    expect($uri)->toContain('firstname=Jane');
    expect($uri)->toContain('lastname=Doe');
    expect($uri)->toContain('dob=1990-01-02T00%3A00%3A00');
});

it('searches patients with only some filters and skips nulls', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->patients()->search(
        lastName: 'Doe',
        status: 'Active',
        pageNumber: 2,
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('lastname=Doe');
    expect($uri)->toContain('status=Active');
    expect($uri)->toContain('pageNumber=2');
    expect($uri)->not->toContain('firstname=');
    expect($uri)->not->toContain('dob=');
});

it('retrieves patient details', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 5, 'Details' => []]);

    $factory->preauthorizedClient()->patients()->details(5);

    expect((string) $factory->lastRequest()->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/patients/5/details');
});

it('updates a patient via POST', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 5]);

    $factory->preauthorizedClient()->patients()->update(5, ['FirstName' => 'Updated']);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())->toBe('/webapi/api/patients/5');
    expect(json_decode((string) $request->getBody(), true))->toBe(['FirstName' => 'Updated']);
});

it('lists pharmacies, clinics, and clinicians for a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->patients()->pharmacies(5);
    $client->patients()->clinics(5);
    $client->patients()->clinicians(5);

    $paths = array_map(
        fn ($entry) => $entry['request']->getUri()->getPath(),
        $factory->history,
    );

    expect($paths)->toBe([
        '/webapi/api/patients/5/pharmacies',
        '/webapi/api/patients/5/clinics',
        '/webapi/api/patients/5/clinicians',
    ]);
});

it('lists self-reported medications with date filters', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->patients()->selfReportedMedications(
        patientId: 5,
        startDate: '2026-01-01',
        endDate: '2026-06-01',
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/patients/5/selfReportedMedications');
    expect($uri)->toContain('startDate=2026-01-01');
    expect($uri)->toContain('endDate=2026-06-01');
});

it('logs medication history consent for a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->patients()->logMedicationHistoryConsent(5, ['Consented' => true]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/logMedicationHistoryConsent');
});

it('runs precheck interactions for a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->patients()->precheckInteractions(5, ['MedicationId' => 1]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/precheckInteractions');
});

it('transfers a patient to another clinic', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->patients()->transfer(5, ['TargetClinicId' => 99]);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())->toBe('/webapi/api/patients/5/transfer');
});

it('sets insurance for a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->patients()->setInsurance(5, ['PlanName' => 'Aetna']);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())->toBe('/webapi/api/patients/5/insurance');
});
it('lists patient prescriptions with DateTime filters and omits null dates', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();

    $client->patients()->prescriptions(
        patientId: 5,
        startDate: new DateTimeImmutable('2026-01-01 08:30:00'),
    );

    $uriWithStart = (string) $factory->history[0]['request']->getUri();
    expect($uriWithStart)->toContain('/api/patients/5/prescriptions');
    expect($uriWithStart)->toContain('startDate=2026-01-01T08%3A30%3A00');
    expect($uriWithStart)->not->toContain('endDate=');

    $client->patients()->prescriptions(patientId: 5);

    $uriWithoutFilters = (string) $factory->history[1]['request']->getUri();
    expect($uriWithoutFilters)->toBe('https://my.staging.dosespot.com/webapi/api/patients/5/prescriptions');
    expect($uriWithoutFilters)->not->toContain('startDate=');
    expect($uriWithoutFilters)->not->toContain('endDate=');
});

it('lists self-reported medications with DateTime filters and omits null dates', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();

    $client->patients()->selfReportedMedications(
        patientId: 5,
        endDate: new DateTimeImmutable('2026-06-01 17:45:00'),
    );

    $uriWithEnd = (string) $factory->history[0]['request']->getUri();
    expect($uriWithEnd)->toContain('/api/patients/5/selfReportedMedications');
    expect($uriWithEnd)->toContain('endDate=2026-06-01T17%3A45%3A00');
    expect($uriWithEnd)->not->toContain('startDate=');

    $client->patients()->selfReportedMedications(patientId: 5);

    $uriWithoutFilters = (string) $factory->history[1]['request']->getUri();
    expect($uriWithoutFilters)->toBe('https://my.staging.dosespot.com/webapi/api/patients/5/selfReportedMedications');
    expect($uriWithoutFilters)->not->toContain('startDate=');
    expect($uriWithoutFilters)->not->toContain('endDate=');
});
