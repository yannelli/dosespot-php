<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Auth\Authenticator;
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

it('refreshes the token after it expires', function () {
    $factory = factory();
    $factory->pushToken('second', 3600);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    $expired = new \Yannelli\DoseSpot\Auth\AccessToken(
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
