<?php

declare(strict_types=1);

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\RequestOptions;
use Yannelli\DoseSpot\Auth\AccessToken;
use Yannelli\DoseSpot\Auth\Authenticator;
use Yannelli\DoseSpot\Config;
use Yannelli\DoseSpot\DoseSpot;
use Yannelli\DoseSpot\Environment;
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

it('serializes backed enums in query strings', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();

    $client->http->get('api/general/check', [
        'status' => \Yannelli\DoseSpot\Enums\PrescriptionStatus::ReadyToSend,
        'metric' => \Yannelli\DoseSpot\Enums\WeightMetric::Kilograms,
        'ignored' => null,
    ]);

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('status=8');
    expect($uri)->toContain('metric=kg');
    expect($uri)->not->toContain('ignored=');
});

it('normalizes nested array query values including enums and null omission', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();

    $client->http->get('api/general/check', [
        'specialty' => [
            \Yannelli\DoseSpot\Enums\PrescriptionStatus::ReadyToSend,
            null,
            \Yannelli\DoseSpot\Enums\WeightMetric::Kilograms,
        ],
        'filters' => [
            'active' => true,
            'requestedAt' => new DateTimeImmutable('2026-07-18 14:04:00'),
            'ignored' => null,
        ],
    ]);

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('specialty%5B0%5D=8');
    expect($uri)->toContain('specialty%5B2%5D=kg');
    expect($uri)->not->toContain('specialty%5B1%5D=');
    expect($uri)->toContain('filters%5Bactive%5D=true');
    expect($uri)->toContain('filters%5BrequestedAt%5D=2026-07-18T14%3A04%3A00');
    expect($uri)->not->toContain('filters%5Bignored%5D=');
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
    $factory->pushResponse(422, ['Message' => 'payload failed validation']);

    $client = $factory->preauthorizedClient();

    expect(fn () => $client->patients()->create([]))
        ->toThrow(ValidationException::class, 'bad data');

    expect(fn () => $client->patients()->create([]))
        ->toThrow(ValidationException::class, 'payload failed validation');
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

it('ignores invalid Retry-After headers on 429 responses', function () {
    $factory = factory();
    $factory->pushResponse(429, ['Message' => 'slow down'], ['Retry-After' => 'eventually']);

    try {
        $factory->preauthorizedClient()->general()->check();
        fail('Expected RateLimitException');
    } catch (RateLimitException $e) {
        expect($e->retryAfter())->toBeNull();
    }
});

it('clamps past HTTP-date Retry-After headers to zero on 429 responses', function () {
    $factory = factory();
    $retryAt = gmdate('D, d M Y H:i:s \G\M\T', time() - 120);
    $factory->pushResponse(429, ['Message' => 'slow down'], ['Retry-After' => $retryAt]);

    try {
        $factory->preauthorizedClient()->general()->check();
        fail('Expected RateLimitException');
    } catch (RateLimitException $e) {
        expect($e->retryAfter())->toBe(0);
    }
});

it('leaves retryAfter null when a 429 response omits Retry-After', function () {
    $factory = factory();
    $factory->pushResponse(429, ['Message' => 'slow down']);

    try {
        $factory->preauthorizedClient()->general()->check();
        fail('Expected RateLimitException');
    } catch (RateLimitException $e) {
        expect($e->statusCode())->toBe(429);
        expect($e->getMessage())->toBe('slow down');
        expect($e->retryAfter())->toBeNull();
    }
});

it('throws a generic ApiException on 500-class responses', function () {
    $factory = factory();
    $factory->pushResponse(500, ['Message' => 'boom']);

    expect(fn () => $factory->preauthorizedClient()->general()->check())
        ->toThrow(ApiException::class, 'boom');
});

it('surfaces OAuth-style error descriptions from API responses', function () {
    $factory = factory();
    $factory->pushResponse(500, ['error' => 'server_error', 'error_description' => 'temporarily unavailable']);

    expect(fn () => $factory->preauthorizedClient()->general()->check())
        ->toThrow(ApiException::class, 'temporarily unavailable');
});

it('surfaces OAuth-style error values when no error description is present', function () {
    $factory = factory();
    $factory->pushResponse(500, ['error' => 'server_error']);

    expect(fn () => $factory->preauthorizedClient()->general()->check())
        ->toThrow(ApiException::class, 'server_error');
});

it('prefers Result.ResultDescription over Message and OAuth error fields', function () {
    $factory = factory();
    $factory->pushResponse(500, [
        'Result' => ['ResultDescription' => 'result description wins'],
        'Message' => 'message ignored',
        'error_description' => 'oauth description ignored',
        'error' => 'error ignored',
    ]);

    expect(fn () => $factory->preauthorizedClient()->general()->check())
        ->toThrow(ApiException::class, 'result description wins');
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

    try {
        $factory->preauthorizedClient()->general()->check();
        fail('Expected ApiException');
    } catch (ApiException $e) {
        expect($e->getMessage())->toContain('Connection timed out');
        expect($e->getPrevious())->toBeInstanceOf(ConnectException::class);
    }
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

it('populates ApiException responseBody and rawResponse on 500 responses', function () {
    $factory = factory();
    $payload = [
        'Message' => 'internal failure',
        'TraceId' => 'req-abcd',
    ];
    $factory->pushResponse(500, $payload);

    try {
        $factory->preauthorizedClient()->general()->check();
        fail('Expected ApiException');
    } catch (ApiException $e) {
        expect($e->statusCode())->toBe(500);
        expect($e->getMessage())->toBe('internal failure');
        expect($e->responseBody())->toBe($payload);
        expect($e->rawResponse())->toBe(json_encode($payload, JSON_THROW_ON_ERROR));
    }
});

it('populates ValidationException responseBody and rawResponse on 422 responses', function () {
    $factory = factory();
    $payload = [
        'Message' => 'payload failed validation',
        'Errors' => [
            'PharmacyId' => ['Required'],
        ],
    ];
    $factory->pushResponse(422, $payload);

    try {
        $factory->preauthorizedClient()->patients()->create([]);
        fail('Expected ValidationException');
    } catch (ValidationException $e) {
        expect($e)->toBeInstanceOf(ApiException::class);
        expect($e->statusCode())->toBe(422);
        expect($e->getMessage())->toBe('payload failed validation');
        expect($e->responseBody())->toBe($payload);
        expect($e->rawResponse())->toBe(json_encode($payload, JSON_THROW_ON_ERROR));
    }
});

it('populates NotFoundException responseBody and rawResponse on 404 responses', function () {
    $factory = factory();
    $payload = [
        'Message' => 'patient not found',
        'TraceId' => 'req-404',
    ];
    $factory->pushResponse(404, $payload);

    try {
        $factory->preauthorizedClient()->patients()->find(999);
        fail('Expected NotFoundException');
    } catch (NotFoundException $e) {
        expect($e)->toBeInstanceOf(ApiException::class);
        expect($e->statusCode())->toBe(404);
        expect($e->getMessage())->toBe('patient not found');
        expect($e->responseBody())->toBe($payload);
        expect($e->rawResponse())->toBe(json_encode($payload, JSON_THROW_ON_ERROR));
    }
});

it('populates RateLimitException responseBody and rawResponse on 429 responses', function () {
    $factory = factory();
    $payload = [
        'Message' => 'slow down',
        'TraceId' => 'req-429',
    ];
    $factory->pushResponse(429, $payload, ['Retry-After' => '7']);

    try {
        $factory->preauthorizedClient()->general()->check();
        fail('Expected RateLimitException');
    } catch (RateLimitException $e) {
        expect($e)->toBeInstanceOf(ApiException::class);
        expect($e->statusCode())->toBe(429);
        expect($e->getMessage())->toBe('slow down');
        expect($e->retryAfter())->toBe(7);
        expect($e->responseBody())->toBe($payload);
        expect($e->rawResponse())->toBe(json_encode($payload, JSON_THROW_ON_ERROR));
    }
});

it('forwards configured timeouts to outbound API requests', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 1]);

    $config = new Config(
        clinicId: '12345',
        clinicKey: 'super-secret-clinic-key-1234567890',
        environment: Environment::Staging,
        userId: 42,
        timeout: 17,
        connectTimeout: 4,
    );

    $guzzle = $factory->guzzle();
    $auth = new Authenticator($config, $guzzle);
    $auth->setToken(new AccessToken(
        token: 'cached-token',
        tokenType: 'bearer',
        expiresAt: time() + 3600,
    ));

    (new DoseSpot($config, $guzzle, $auth))->general()->check();

    $options = $factory->history[0]['options'];
    expect($options[RequestOptions::TIMEOUT])->toBe(17);
    expect($options[RequestOptions::CONNECT_TIMEOUT])->toBe(4);
});
