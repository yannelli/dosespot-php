<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Config;
use Yannelli\DoseSpot\Environment;
use Yannelli\DoseSpot\Exceptions\DoseSpotException;

it('builds staging URLs by default', function () {
    $config = new Config(clinicId: '1', clinicKey: 'k', environment: Environment::Staging);

    expect($config->baseUrl)->toBe('https://my.staging.dosespot.com/webapi');
    expect($config->tokenUrl())->toBe('https://my.staging.dosespot.com/webapi/token');
    expect($config->apiUrl('api/general/check'))->toBe('https://my.staging.dosespot.com/webapi/api/general/check');
});

it('uses production URLs when configured', function () {
    $config = new Config(clinicId: '1', clinicKey: 'k', environment: Environment::Production);

    expect($config->baseUrl)->toBe('https://my.dosespot.com/webapi');
});

it('respects a custom base URL', function () {
    $config = new Config(clinicId: '1', clinicKey: 'k', baseUrl: 'https://example.test/webapi/');

    expect($config->baseUrl)->toBe('https://example.test/webapi');
    expect($config->apiUrl('/api/general/check'))->toBe('https://example.test/webapi/api/general/check');
});

it('requires a clinic id and clinic key', function () {
    expect(fn () => new Config(clinicId: '', clinicKey: 'k'))->toThrow(DoseSpotException::class);
    expect(fn () => new Config(clinicId: '1', clinicKey: ''))->toThrow(DoseSpotException::class);
});

it('clones with a new user id and preserves a custom base URL', function () {
    $config = new Config(clinicId: '1', clinicKey: 'k', baseUrl: 'https://example.test/webapi');

    $next = $config->withUserId(99);

    expect($config->userId)->toBeNull();
    expect($next->userId)->toBe(99);
    expect($next->baseUrl)->toBe('https://example.test/webapi');
});

it('rejects non-positive timeouts', function () {
    expect(fn () => new Config(clinicId: '1', clinicKey: 'k', timeout: 0))
        ->toThrow(DoseSpotException::class);
    expect(fn () => new Config(clinicId: '1', clinicKey: 'k', connectTimeout: -1))
        ->toThrow(DoseSpotException::class);
});
