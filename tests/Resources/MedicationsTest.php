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
