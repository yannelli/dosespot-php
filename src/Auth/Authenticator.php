<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Auth;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Yannelli\DoseSpot\Config;
use Yannelli\DoseSpot\Exceptions\AuthenticationException;

final class Authenticator
{
    private ?AccessToken $cachedToken = null;

    public function __construct(
        private readonly Config $config,
        private readonly ClientInterface $httpClient,
        private readonly KeyGenerator $keyGenerator = new KeyGenerator(),
    ) {
    }

    public function token(): AccessToken
    {
        if ($this->cachedToken !== null && ! $this->cachedToken->isExpired()) {
            return $this->cachedToken;
        }

        return $this->cachedToken = $this->requestToken();
    }

    public function forget(): void
    {
        $this->cachedToken = null;
    }

    public function setToken(AccessToken $token): void
    {
        $this->cachedToken = $token;
    }

    private function requestToken(): AccessToken
    {
        $password = $this->keyGenerator->generate($this->config->clinicKey);

        $form = [
            'grant_type' => 'password',
            'Username' => $this->config->clinicId,
            'Password' => $password,
        ];

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        if ($this->config->userId !== null) {
            $headers['X-DoseSpot-UserId'] = (string) $this->config->userId;
        }

        try {
            $response = $this->httpClient->request('POST', $this->config->tokenUrl(), [
                RequestOptions::HEADERS => $headers,
                RequestOptions::FORM_PARAMS => $form,
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::TIMEOUT => $this->config->timeout,
                RequestOptions::CONNECT_TIMEOUT => $this->config->connectTimeout,
            ]);
        } catch (GuzzleException $e) {
            throw new AuthenticationException(
                'Failed to reach DoseSpot token endpoint: '.$e->getMessage(),
                $e->getCode(),
                $e,
            );
        }

        $status = $response->getStatusCode();
        $body = (string) $response->getBody();
        $decoded = json_decode($body, true);

        if ($status < 200 || $status >= 300 || ! is_array($decoded) || ! isset($decoded['access_token'])) {
            $message = $this->extractErrorMessage($decoded, $status);

            throw new AuthenticationException($message, $status);
        }

        $expiresIn = isset($decoded['expires_in']) ? (int) $decoded['expires_in'] : 3600;

        return new AccessToken(
            token: (string) $decoded['access_token'],
            tokenType: (string) ($decoded['token_type'] ?? 'Bearer'),
            expiresAt: time() + $expiresIn,
            userId: isset($decoded['userId']) ? (int) $decoded['userId'] : $this->config->userId,
        );
    }

    private function extractErrorMessage(mixed $decoded, int $status): string
    {
        if (! is_array($decoded)) {
            return 'DoseSpot token request failed with HTTP '.$status;
        }

        if (isset($decoded['Result']['ResultDescription'])) {
            return (string) $decoded['Result']['ResultDescription'];
        }

        foreach (['error_description', 'Message', 'error'] as $key) {
            if (isset($decoded[$key])) {
                return (string) $decoded[$key];
            }
        }

        return 'DoseSpot token request failed with HTTP '.$status;
    }
}
