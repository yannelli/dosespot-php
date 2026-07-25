<?php

declare(strict_types=1);

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
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

it('prefers userId from the token response over the config value', function () {
    $factory = factory();
    $factory->pushResponse(200, [
        'access_token' => 'response-user-token',
        'token_type' => 'bearer',
        'expires_in' => 3600,
        'userId' => 777,
    ]);

    $auth = new Authenticator($factory->config(), $factory->guzzle());
    $token = $auth->token();

    expect($token->token)->toBe('response-user-token');
    expect($token->userId)->toBe(777);
    expect($factory->config()->userId)->toBe(42);
});

it('falls back to the configured user id when the token response omits userId', function () {
    $factory = factory();
    $factory->pushToken('config-user-token');

    $auth = new Authenticator($factory->config(), $factory->guzzle());
    $token = $auth->token();

    expect($token->token)->toBe('config-user-token');
    expect($token->userId)->toBe(42);
});

it('leaves userId null when neither the response nor config provides one', function () {
    $factory = factory();
    $factory->pushToken('no-user-token');

    $config = new Config(
        clinicId: '12345',
        clinicKey: 'super-secret-clinic-key-1234567890',
        environment: Environment::Staging,
    );

    $auth = new Authenticator($config, $factory->guzzle());
    $token = $auth->token();

    expect($token->token)->toBe('no-user-token');
    expect($token->userId)->toBeNull();
});

it('defaults token_type to Bearer and expires_in to 3600 when omitted', function () {
    $factory = factory();
    $factory->pushResponse(200, [
        'access_token' => 'minimal-token',
    ]);

    $before = time();
    $auth = new Authenticator($factory->config(), $factory->guzzle());
    $token = $auth->token();
    $after = time();

    expect($token->token)->toBe('minimal-token');
    expect($token->tokenType)->toBe('Bearer');
    expect($token->authorizationHeader())->toBe('Bearer minimal-token');
    expect($token->expiresAt)->toBeGreaterThanOrEqual($before + 3600);
    expect($token->expiresAt)->toBeLessThanOrEqual($after + 3600);
    expect($token->isExpired())->toBeFalse();
});

it('forwards configured timeouts to token requests', function () {
    $factory = factory();
    $factory->pushToken('timeout-token');

    $config = new Config(
        clinicId: '12345',
        clinicKey: 'super-secret-clinic-key-1234567890',
        environment: Environment::Staging,
        userId: 42,
        timeout: 21,
        connectTimeout: 6,
    );

    (new Authenticator($config, $factory->guzzle()))->token();

    $options = $factory->history[0]['options'];
    expect($options[RequestOptions::TIMEOUT])->toBe(21);
    expect($options[RequestOptions::CONNECT_TIMEOUT])->toBe(6);
});

it('throws when a successful token response omits access_token', function () {
    $factory = factory();
    $factory->pushResponse(200, [
        'token_type' => 'bearer',
        'expires_in' => 3600,
    ]);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())
        ->toThrow(AuthenticationException::class, 'DoseSpot token request failed with HTTP 200');
});

it('falls back to an HTTP status message when the token response body is not a JSON object', function () {
    $factory = factory();
    $factory->pushRaw(new \GuzzleHttp\Psr7\Response(
        503,
        ['Content-Type' => 'text/html'],
        '<html>bad gateway</html>',
    ));
    $factory->pushRaw(new \GuzzleHttp\Psr7\Response(
        400,
        ['Content-Type' => 'application/json'],
        '"unexpected"',
    ));
    $factory->pushRaw(new \GuzzleHttp\Psr7\Response(500, [], ''));

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())
        ->toThrow(AuthenticationException::class, 'DoseSpot token request failed with HTTP 503');

    expect(fn () => $auth->token())
        ->toThrow(AuthenticationException::class, 'DoseSpot token request failed with HTTP 400');

    expect(fn () => $auth->token())
        ->toThrow(AuthenticationException::class, 'DoseSpot token request failed with HTTP 500');
});

it('uses a custom KeyGenerator for the token password grant', function () {
    $factory = factory();
    $factory->pushToken('custom-key-token');

    $generator = new class () extends \Yannelli\DoseSpot\Auth\KeyGenerator {
        public function generate(string $clinicKey, ?string $seed = null): string
        {
            return 'deterministic-custom-password-from-test';
        }
    };

    (new Authenticator($factory->config(), $factory->guzzle(), $generator))->token();

    $request = $factory->lastRequest();
    parse_str((string) $request->getBody(), $form);

    expect($form['Password'])->toBe('deterministic-custom-password-from-test');
    expect($form['grant_type'])->toBe('password');
    expect($form['Username'])->toBe('12345');
});

it('falls back to an HTTP status message when the JSON error object has no known keys', function () {
    $factory = factory();
    $factory->pushResponse(401, ['foo' => 'bar', 'code' => 12]);
    $factory->pushResponse(503, []);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())
        ->toThrow(AuthenticationException::class, 'DoseSpot token request failed with HTTP 401');

    expect(fn () => $auth->token())
        ->toThrow(AuthenticationException::class, 'DoseSpot token request failed with HTTP 503');
});

it('prefers Result.ResultDescription over Message and OAuth error fields', function () {
    $factory = factory();
    $factory->pushResponse(401, [
        'Result' => ['ResultDescription' => 'result description wins'],
        'Message' => 'message ignored',
        'error_description' => 'oauth description ignored',
        'error' => 'error ignored',
    ]);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())
        ->toThrow(AuthenticationException::class, 'result description wins');
});

it('falls through Result without ResultDescription to error_description', function () {
    $factory = factory();
    $factory->pushResponse(401, [
        'Result' => ['ResultCode' => 'ERROR'],
        'error_description' => 'oauth description wins after empty Result',
        'Message' => 'message ignored',
        'error' => 'error ignored',
    ]);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())
        ->toThrow(AuthenticationException::class, 'oauth description wins after empty Result');
});

it('prefers Message over error when higher-priority fields are absent', function () {
    $factory = factory();
    $factory->pushResponse(401, [
        'Message' => 'message wins over bare error',
        'error' => 'error ignored',
    ]);

    $auth = new Authenticator($factory->config(), $factory->guzzle());

    expect(fn () => $auth->token())
        ->toThrow(AuthenticationException::class, 'message wins over bare error');
});
