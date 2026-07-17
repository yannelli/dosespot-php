<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Auth\AccessToken;

it('formats the authorization header with the token type and token', function () {
    $token = new AccessToken(
        token: 'abc123',
        tokenType: 'bearer',
        expiresAt: time() + 3600,
    );

    expect($token->authorizationHeader())->toBe('Bearer abc123');
});

it('treats tokens inside the expiry leeway as expired', function () {
    $token = new AccessToken(
        token: 'abc123',
        tokenType: 'bearer',
        expiresAt: time() + 20,
    );

    expect($token->isExpired())->toBeTrue();
    expect($token->isExpired(leewaySeconds: 0))->toBeFalse();
});

it('normalizes mixed-case and uppercase token types to Bearer casing', function () {
    $upper = new AccessToken(
        token: 'abc123',
        tokenType: 'BEARER',
        expiresAt: time() + 3600,
    );
    $mixed = new AccessToken(
        token: 'xyz789',
        tokenType: 'BeArEr',
        expiresAt: time() + 3600,
    );

    expect($upper->authorizationHeader())->toBe('Bearer abc123');
    expect($mixed->authorizationHeader())->toBe('Bearer xyz789');
});
