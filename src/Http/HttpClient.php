<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Yannelli\DoseSpot\Auth\Authenticator;
use Yannelli\DoseSpot\Config;
use Yannelli\DoseSpot\Exceptions\ApiException;
use Yannelli\DoseSpot\Exceptions\AuthenticationException;
use Yannelli\DoseSpot\Exceptions\NotFoundException;
use Yannelli\DoseSpot\Exceptions\RateLimitException;
use Yannelli\DoseSpot\Exceptions\ValidationException;

final class HttpClient
{
    public function __construct(
        private readonly Config $config,
        private readonly ClientInterface $httpClient,
        private readonly Authenticator $authenticator,
    ) {
    }

    public function get(string $path, array $query = []): Response
    {
        return $this->request('GET', $path, query: $query);
    }

    public function post(string $path, ?array $body = null, array $query = []): Response
    {
        return $this->request('POST', $path, body: $body, query: $query);
    }

    public function put(string $path, ?array $body = null, array $query = []): Response
    {
        return $this->request('PUT', $path, body: $body, query: $query);
    }

    public function delete(string $path, array $query = []): Response
    {
        return $this->request('DELETE', $path, query: $query);
    }

    public function request(string $method, string $path, ?array $body = null, array $query = []): Response
    {
        $token = $this->authenticator->token();

        $options = [
            RequestOptions::HEADERS => [
                'Authorization' => $token->authorizationHeader(),
                'Accept' => 'application/json',
            ],
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::TIMEOUT => $this->config->timeout,
            RequestOptions::CONNECT_TIMEOUT => $this->config->connectTimeout,
        ];

        if ($query !== []) {
            $options[RequestOptions::QUERY] = $this->normalizeQuery($query);
        }

        if ($body !== null) {
            $options[RequestOptions::JSON] = $body;
        }

        try {
            $raw = $this->httpClient->request($method, $this->config->apiUrl($path), $options);
        } catch (GuzzleException $e) {
            throw new ApiException(
                'HTTP request to DoseSpot failed: '.$e->getMessage(),
                $e->getCode(),
                previous: $e,
            );
        }

        $response = new Response($raw);

        if (! $response->isSuccessful()) {
            $this->throwForStatus($response);
        }

        return $response;
    }

    private function normalizeQuery(array $query): array
    {
        $out = [];

        foreach ($query as $key => $value) {
            if ($value === null) {
                continue;
            }

            if ($value instanceof \DateTimeInterface) {
                $out[$key] = $value->format('Y-m-d\TH:i:s');

                continue;
            }

            if (is_bool($value)) {
                $out[$key] = $value ? 'true' : 'false';

                continue;
            }

            $out[$key] = $value;
        }

        return $out;
    }

    private function throwForStatus(Response $response): never
    {
        $status = $response->statusCode();
        $body = $response->body();
        $decoded = $response->json();
        $message = $this->extractMessage($decoded, $body, $status);

        throw match (true) {
            $status === 401, $status === 403 => new AuthenticationException($message, $status),
            $status === 404 => new NotFoundException($message, $status, $decoded, $body),
            $status === 422, $status === 400 => new ValidationException($message, $status, $decoded, $body),
            $status === 429 => new RateLimitException(
                message: $message,
                statusCode: $status,
                responseBody: $decoded,
                rawResponse: $body,
                retryAfter: $this->parseRetryAfter($response),
            ),
            default => new ApiException($message, $status, $decoded, $body),
        };
    }

    private function extractMessage(array $decoded, string $body, int $status): string
    {
        if (isset($decoded['Result']['ResultDescription'])) {
            return (string) $decoded['Result']['ResultDescription'];
        }

        if (isset($decoded['Message'])) {
            return (string) $decoded['Message'];
        }

        if (isset($decoded['error_description'])) {
            return (string) $decoded['error_description'];
        }

        if (isset($decoded['error'])) {
            return (string) $decoded['error'];
        }

        return $body !== '' ? $body : ('DoseSpot API returned HTTP '.$status);
    }

    private function parseRetryAfter(Response $response): ?int
    {
        $header = $response->header('Retry-After');

        if ($header === null) {
            return null;
        }

        $header = trim($header);

        if (ctype_digit($header)) {
            return (int) $header;
        }

        $timestamp = strtotime($header);

        return $timestamp === false ? null : max(0, $timestamp - time());
    }
}
