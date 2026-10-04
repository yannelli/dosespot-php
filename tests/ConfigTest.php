<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Config;
use Yannelli\DoseSpot\Environment;
use Yannelli\DoseSpot\Exceptions\DoseSpotException;

it('builds staging v2 URLs', function () {
    $config = new Config(
        clinicId: '1',
        clinicKey: 'k',
        subscriptionKey: 'sub',
        userId: 7,
        environment: Environment::Staging,
    );

    expect($config->baseUrl)->toBe('https://my.staging.dosespot.com/webapi/v2');
    expect($config->tokenUrl())->toBe('https://my.staging.dosespot.com/webapi/v2/connect/token');
    expect($config->apiUrl('api/general/check'))->toBe('https://my.staging.dosespot.com/webapi/v2/api/general/check');
});

it('uses production v2 URLs when configured', function () {
    $config = new Config(
        clinicId: '1',
        clinicKey: 'k',
        subscriptionKey: 'sub',
        userId: 7,
        environment: Environment::Production,
    );

    expect($config->baseUrl)->toBe('https://my.dosespot.com/webapi/v2');
});

it('respects a custom base URL', function () {
    $config = new Config(
        clinicId: '1',
        clinicKey: 'k',
        subscriptionKey: 'sub',
        userId: 7,
        baseUrl: 'https://example.test/webapi/v2/',
    );

    expect($config->baseUrl)->toBe('https://example.test/webapi/v2');
    expect($config->apiUrl('/api/general/check'))->toBe('https://example.test/webapi/v2/api/general/check');
});

it('requires clinic credentials, a subscription key, and a clinician id', function () {
    expect(fn () => new Config(clinicId: '', clinicKey: 'k', subscriptionKey: 'sub', userId: 7))
        ->toThrow(DoseSpotException::class);
    expect(fn () => new Config(clinicId: '1', clinicKey: '', subscriptionKey: 'sub', userId: 7))
        ->toThrow(DoseSpotException::class);
    expect(fn () => new Config(clinicId: '1', clinicKey: 'k', subscriptionKey: '', userId: 7))
        ->toThrow(DoseSpotException::class);
    expect(fn () => new Config(clinicId: '1', clinicKey: 'k', subscriptionKey: 'sub', userId: 0))
        ->toThrow(DoseSpotException::class);
});

it('clones with a new user id and preserves a custom base URL', function () {
    $config = new Config(
        clinicId: '1',
        clinicKey: 'k',
        subscriptionKey: 'sub',
        userId: 7,
        timeout: 15,
        connectTimeout: 5,
        baseUrl: 'https://example.test/webapi/v2',
    );

    $next = $config->withUserId(99);

    expect($config->userId)->toBe(7);
    expect($next->userId)->toBe(99);
    expect($next->subscriptionKey)->toBe('sub');
    expect($next->baseUrl)->toBe('https://example.test/webapi/v2');
    expect($next->timeout)->toBe(15);
    expect($next->connectTimeout)->toBe(5);
});

it('rejects non-positive timeouts', function () {
    expect(fn () => new Config(clinicId: '1', clinicKey: 'k', subscriptionKey: 'sub', userId: 7, timeout: 0))
        ->toThrow(DoseSpotException::class);
    expect(fn () => new Config(clinicId: '1', clinicKey: 'k', subscriptionKey: 'sub', userId: 7, connectTimeout: -1))
        ->toThrow(DoseSpotException::class);
});
