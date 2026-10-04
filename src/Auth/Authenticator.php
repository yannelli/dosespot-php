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

    /**
     * DoseSpot v2 token grant. The password is the clinic key, not a user password.
     *
     * @see https://my.dosespot.com/webapi/v2/connect/token
     */
    private function requestToken(): AccessToken
    {
        $form = [
            'grant_type' => 'password',
            'client_id' => $this->config->clinicId,
            'client_secret' => $this->config->clinicKey,
            'username' => (string) $this->config->userId,
            'password' => $this->config->clinicKey,
            'scope' => 'api',
        ];

        try {
            $response = $this->httpClient->request('POST', $this->config->tokenUrl(), [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Subscription-Key' => $this->config->subscriptionKey,
                ],
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
            $description = trim((string) $decoded['Result']['ResultDescription']);

            if ($description !== '') {
                return $description;
            }
        }

        foreach (['error_description', 'Message', 'error'] as $key) {
            if (! isset($decoded[$key])) {
                continue;
            }

            $value = trim((string) $decoded[$key]);

            if ($value !== '') {
                return $value;
            }
        }

        return 'DoseSpot token request failed with HTTP '.$status;
    }
}
