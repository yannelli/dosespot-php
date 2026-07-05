<?php

declare(strict_types=1);

it('searches medications by name', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->medications()->search('lisinopril');

    expect((string) $factory->lastRequest()->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/medications/search?name=lisinopril');
});

it('fetches medication history with date range', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->medications()->history(
        patientId: 99,
        start: '2026-01-01',
        end: '2026-06-01',
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/patients/99/medications/history');
    expect($uri)->toContain('start=2026-01-01');
    expect($uri)->toContain('end=2026-06-01');
});

it('performs a basic medication search', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->medications()->basicSearch('aspirin');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/medications/basicSearch');
    expect($uri)->toContain('name=aspirin');
});

it('selects a medication by rxCui, name, and strength', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->medications()->select(
        rxCui: '197364',
        name: 'lisinopril',
        strength: '10 MG',
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/medications/select');
    expect($uri)->toContain('RxCUI=197364');
    expect($uri)->toContain('Name=lisinopril');
    expect($uri)->toContain('Strength=10%20MG');
});

it('checks drug-drug interactions for a patient', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->medications()->interactions(5);

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/medications/interactions');
});
