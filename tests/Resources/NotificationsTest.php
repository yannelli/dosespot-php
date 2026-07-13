<?php

declare(strict_types=1);

it('builds bracket-indexed clinician ids for batch counts', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->notifications()->batchCounts([10, 20, 30]);

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('clinicianId%5B0%5D=10');
    expect($uri)->toContain('clinicianId%5B1%5D=20');
    expect($uri)->toContain('clinicianId%5B2%5D=30');
});

it('fetches notification counts', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Counts' => []]);

    $factory->preauthorizedClient()->notifications()->counts();

    expect((string) $factory->lastRequest()->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/notifications/counts');
});

it('fetches notification errors', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->notifications()->errors();

    expect((string) $factory->lastRequest()->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/notifications/errors');
});

it('fetches errors by client with date range', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->notifications()->errorsByClient(
        startDate: new DateTimeImmutable('2026-01-01 00:00:00'),
        endDate: '2026-06-01',
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/notifications/errorsByClient');
    expect($uri)->toContain('startDate=2026-01-01T00%3A00%3A00');
    expect($uri)->toContain('endDate=2026-06-01');
});

it('fetches errors by client with optional filters and omits null query params', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();

    $client->notifications()->errorsByClient(
        startDate: new DateTimeImmutable('2026-03-15 12:30:00'),
    );
    $client->notifications()->errorsByClient();

    $uriWithStart = (string) $factory->history[0]['request']->getUri();
    expect($uriWithStart)->toContain('/api/notifications/errorsByClient');
    expect($uriWithStart)->toContain('startDate=2026-03-15T12%3A30%3A00');
    expect($uriWithStart)->not->toContain('endDate=');

    $uriWithoutFilters = (string) $factory->history[1]['request']->getUri();
    expect($uriWithoutFilters)->toBe('https://my.staging.dosespot.com/webapi/api/notifications/errorsByClient');
    expect($uriWithoutFilters)->not->toContain('startDate=');
    expect($uriWithoutFilters)->not->toContain('endDate=');
});
