<?php

declare(strict_types=1);

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Yannelli\DoseSpot\Auth\AccessToken;
use Yannelli\DoseSpot\Auth\Authenticator;
use Yannelli\DoseSpot\Config;
use Yannelli\DoseSpot\Environment;
use Yannelli\DoseSpot\Exceptions\AuthenticationException;

it('requests a token using the password grant and caches it', function () {
    $factory = factory();
    $factory->pushToken('access-abc', 3600);

    $auth = new Authenticator($factory->config(), $factory->guzzle());
    $token = $auth->token();

    expect($token->token)->toBe('access-abc');
    expect($token->tokenType)->toBe('bearer');
    expect($token->isExpired())->toBeFalse();

    // Second call should hit the cache (no second HTTP request).
    $cached = $auth->token();
    expect($cached)->toBe($token);
    expect($factory->history)->toHaveCount(1);
});

it('sends clinic credentials in the form body', function () {
    $factory = factory();
    $factory->pushToken();

    (new Authenticator($factory->config(), $factory->guzzle()))->token();

    $request = $factory->lastRequest();
    parse_str((string) $request->getBody(), $form);

    expect($form['grant_type'])->toBe('password');
    expect($form['Username'])->toBe('12345');
    expect($form['Password'])->toBeString()->not->toBeEmpty();
    expect($request->getHeaderLine('X-DoseSpot-UserId'))->toBe('42');
});

it('throws an AuthenticationException when the token request fails', function () {
    $factory = factory();
    $factory->pushResponse(400, ['error' => 'invalid_client', 'error_description' => 'bad clinic']);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())->toThrow(AuthenticationException::class, 'bad clinic');
});

it('preserves network exceptions from token requests', function () {
    $factory = factory();
    $factory->mockHandler->append(
        new ConnectException('Connection timed out', new Request('POST', 'token')),
    );

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    try {
        $auth->token();
        fail('Expected AuthenticationException');
    } catch (AuthenticationException $e) {
        expect($e->getMessage())->toContain('Connection timed out');
        expect($e->getPrevious())->toBeInstanceOf(ConnectException::class);
    }
});

it('surfaces token error values when no error description is present', function () {
    $factory = factory();
    $factory->pushResponse(400, ['error' => 'invalid_client']);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())->toThrow(AuthenticationException::class, 'invalid_client');
});

it('surfaces token message values when no error description is present', function () {
    $factory = factory();
    $factory->pushResponse(401, ['Message' => 'clinic unauthorized']);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())->toThrow(AuthenticationException::class, 'clinic unauthorized');
});

it('surfaces token result descriptions when present', function () {
    $factory = factory();
    $factory->pushResponse(400, ['Result' => ['ResultDescription' => 'clinic key rejected']]);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())->toThrow(AuthenticationException::class, 'clinic key rejected');
});

it('refreshes the token after it expires', function () {
    $factory = factory();
    $factory->pushToken('second', 3600);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    $expired = new AccessToken(
        token: 'first',
        tokenType: 'bearer',
        expiresAt: time() - 60,
    );

    $auth->setToken($expired);

    expect($auth->token()->token)->toBe('second');
});

it('forgets a cached token on demand', function () {
    $factory = factory();
    $factory->pushToken('first');
    $factory->pushToken('second');

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect($auth->token()->token)->toBe('first');

    $auth->forget();

    expect($auth->token()->token)->toBe('second');
});

it('omits the user id header when config has no user id', function () {
    $factory = factory();
    $factory->pushToken();

    $config = new Config(
        clinicId: '12345',
        clinicKey: 'super-secret-clinic-key-1234567890',
        environment: Environment::Staging,
    );

    (new Authenticator($config, $factory->guzzle()))->token();

    $request = $factory->lastRequest();
    expect($request->hasHeader('X-DoseSpot-UserId'))->toBeFalse();
});
