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
