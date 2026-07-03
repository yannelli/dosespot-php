<?php

declare(strict_types=1);

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Yannelli\DoseSpot\Exceptions\ApiException;
use Yannelli\DoseSpot\Exceptions\AuthenticationException;
use Yannelli\DoseSpot\Exceptions\NotFoundException;
use Yannelli\DoseSpot\Exceptions\RateLimitException;
use Yannelli\DoseSpot\Exceptions\ValidationException;

it('adds the bearer token to outgoing requests', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 1]);

    $client = $factory->preauthorizedClient();

    $client->general()->check();

    $request = $factory->lastRequest();
    expect($request->getHeaderLine('Authorization'))->toBe('Bearer cached-token');
    expect($request->getHeaderLine('Accept'))->toBe('application/json');
    expect((string) $request->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/general/check');
});

it('serializes booleans, datetimes, and skips nulls in query strings', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();

    $client->http->get('api/general/check', [
        'active' => true,
        'includeInactive' => false,
        'requestedAt' => new DateTimeImmutable('1990-01-02 03:04:05'),
        'ignored' => null,
    ]);

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('active=true');
    expect($uri)->toContain('includeInactive=false');
    expect($uri)->toContain('requestedAt=1990-01-02T03%3A04%3A05');
    expect($uri)->not->toContain('ignored=');
});

it('preserves zero-like query values', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();

    $client->http->get('api/general/check', [
        'page' => 0,
        'search' => '0',
    ]);

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('page=0');
    expect($uri)->toContain('search=0');
});

it('throws a NotFoundException on 404', function () {
    $factory = factory();
    $factory->pushResponse(404, ['Message' => 'patient not found']);

    expect(fn () => $factory->preauthorizedClient()->patients()->find(999))
        ->toThrow(NotFoundException::class, 'patient not found');
});

it('throws a ValidationException on 400 / 422', function () {
    $factory = factory();
    $factory->pushResponse(400, ['Result' => ['ResultDescription' => 'bad data']]);

    expect(fn () => $factory->preauthorizedClient()->patients()->create([]))
        ->toThrow(ValidationException::class, 'bad data');
});

it('throws an AuthenticationException on 401', function () {
    $factory = factory();
    $factory->pushResponse(401, ['Message' => 'unauthorized']);

    expect(fn () => $factory->preauthorizedClient()->general()->check())
        ->toThrow(AuthenticationException::class, 'unauthorized');
});

it('throws a RateLimitException on 429 and surfaces Retry-After', function () {
    $factory = factory();
    $factory->pushResponse(429, ['Message' => 'slow down'], ['Retry-After' => '5']);

    try {
        $factory->preauthorizedClient()->general()->check();
        fail('Expected RateLimitException');
    } catch (RateLimitException $e) {
        expect($e->statusCode())->toBe(429);
        expect($e->retryAfter())->toBe(5);
    }
});

it('parses HTTP-date Retry-After headers on 429 responses', function () {
    $factory = factory();
    $retryAt = gmdate('D, d M Y H:i:s \G\M\T', time() + 120);
    $factory->pushResponse(429, ['Message' => 'slow down'], ['Retry-After' => $retryAt]);

    try {
        $factory->preauthorizedClient()->general()->check();
        fail('Expected RateLimitException');
    } catch (RateLimitException $e) {
        expect($e->retryAfter())
            ->toBeGreaterThan(0)
            ->toBeLessThanOrEqual(120);
    }
});

it('throws a generic ApiException on 500-class responses', function () {
    $factory = factory();
    $factory->pushResponse(500, ['Message' => 'boom']);

    expect(fn () => $factory->preauthorizedClient()->general()->check())
        ->toThrow(ApiException::class, 'boom');
});

it('throws an AuthenticationException on 403', function () {
    $factory = factory();
    $factory->pushResponse(403, ['Message' => 'forbidden']);

    expect(fn () => $factory->preauthorizedClient()->general()->check())
        ->toThrow(AuthenticationException::class, 'forbidden');
});

it('wraps a network-level GuzzleException in an ApiException', function () {
    $factory = factory();
    $factory->mockHandler->append(
        new ConnectException('Connection timed out', new Request('GET', 'test')),
    );

    expect(fn () => $factory->preauthorizedClient()->general()->check())
        ->toThrow(ApiException::class, 'Connection timed out');
});

it('falls back to the raw body when no known error field is present', function () {
    $factory = factory();
    $factory->pushResponse(502, ['upstream' => 'unavailable']);

    try {
        $factory->preauthorizedClient()->general()->check();
    } catch (ApiException $e) {
        expect($e->getMessage())->toContain('upstream');
    }
});

it('falls back to an HTTP status message when the response body is empty', function () {
    $factory = factory();
    $factory->mockHandler->append(new GuzzleResponse(503, [], ''));

    expect(fn () => $factory->preauthorizedClient()->general()->check())
        ->toThrow(ApiException::class, 'DoseSpot API returned HTTP 503');
});
