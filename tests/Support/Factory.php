<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Tests\Support;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Yannelli\DoseSpot\Auth\AccessToken;
use Yannelli\DoseSpot\Auth\Authenticator;
use Yannelli\DoseSpot\Config;
use Yannelli\DoseSpot\DoseSpot;
use Yannelli\DoseSpot\Environment;

class Factory
{
    /** @var list<array{0:string,1:RequestInterface,2:array}> */
    public array $history = [];

    public MockHandler $mockHandler;

    public function __construct()
    {
        $this->mockHandler = new MockHandler;
    }

    public function pushResponse(int $status = 200, array $json = [], array $headers = []): void
    {
        $headers = array_merge(['Content-Type' => 'application/json'], $headers);
        $this->mockHandler->append(new Response($status, $headers, json_encode($json, JSON_THROW_ON_ERROR)));
    }

    public function pushRaw(Response $response): void
    {
        $this->mockHandler->append($response);
    }

    public function pushToken(string $token = 'test-token', int $expiresIn = 3600): void
    {
        $this->pushResponse(200, [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresIn,
        ]);
    }

    public function guzzle(): GuzzleClient
    {
        $stack = HandlerStack::create($this->mockHandler);
        $stack->push(Middleware::history($this->history));

        return new GuzzleClient(['handler' => $stack]);
    }

    public function config(?Environment $environment = null): Config
    {
        return new Config(
            clinicId: '12345',
            clinicKey: 'super-secret-clinic-key-1234567890',
            environment: $environment ?? Environment::Staging,
            userId: 42,
        );
    }

    public function client(?AccessToken $token = null): DoseSpot
    {
        $config = $this->config();
        $guzzle = $this->guzzle();
        $auth = new Authenticator($config, $guzzle);

        if ($token !== null) {
            $auth->setToken($token);
        }

        return new DoseSpot($config, $guzzle, $auth);
    }

    public function preauthorizedClient(): DoseSpot
    {
        return $this->client(new AccessToken(
            token: 'cached-token',
            tokenType: 'bearer',
            expiresAt: time() + 3600,
        ));
    }

    public function lastRequest(): RequestInterface
    {
        return $this->history[array_key_last($this->history)]['request'];
    }
}
