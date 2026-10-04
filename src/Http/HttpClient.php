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

    public function patch(string $path, ?array $body = null, array $query = []): Response
    {
        return $this->request('PATCH', $path, body: $body, query: $query);
    }

    public function delete(string $path, array $query = [], ?array $body = null): Response
    {
        return $this->request('DELETE', $path, body: $body, query: $query);
    }

    public function request(string $method, string $path, ?array $body = null, array $query = []): Response
    {
        $token = $this->authenticator->token();

        $options = [
            RequestOptions::HEADERS => [
                'Authorization' => $token->authorizationHeader(),
                'Accept' => 'application/json',
                'Subscription-Key' => $this->config->subscriptionKey,
            ],
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::TIMEOUT => $this->config->timeout,
            RequestOptions::CONNECT_TIMEOUT => $this->config->connectTimeout,
        ];

        $queryString = $this->encodeQuery($query);

        if ($queryString !== '') {
            $options[RequestOptions::QUERY] = $queryString;
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

        $this->throwIfResultFailed($response);

        return $response;
    }

    /**
     * Encode query parameters the way DoseSpot's swagger describes them.
     *
     * Sequential arrays use collectionFormat "multi" (repeated keys).
     * Associative arrays use bracket notation. Nulls are omitted.
     *
     * @param  list<string>  $pairs
     */
    private function encodeQuery(array $query): string
    {
        $pairs = [];

        foreach ($query as $key => $value) {
            $this->appendQueryValue($pairs, (string) $key, $value);
        }

        return implode('&', $pairs);
    }

    /**
     * @param  list<string>  $pairs
     */
    private function appendQueryValue(array &$pairs, string $key, mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if ($value instanceof \DateTimeInterface) {
            $pairs[] = rawurlencode($key).'='.rawurlencode($value->format('Y-m-d\TH:i:s'));

            return;
        }

        if ($value instanceof \BackedEnum) {
            $pairs[] = rawurlencode($key).'='.rawurlencode((string) $value->value);

            return;
        }

        if (is_bool($value)) {
            $pairs[] = rawurlencode($key).'='.($value ? 'true' : 'false');

            return;
        }

        if (is_array($value)) {
            if ($this->isSequence($value)) {
                foreach ($value as $item) {
                    $this->appendQueryValue($pairs, $key, $item);
                }

                return;
            }

            foreach ($value as $childKey => $child) {
                $this->appendQueryValue($pairs, $key.'['.$childKey.']', $child);
            }

            return;
        }

        $pairs[] = rawurlencode($key).'='.rawurlencode((string) $value);
    }

    private function isSequence(array $value): bool
    {
        foreach (array_keys($value) as $index) {
            if (! is_int($index)) {
                return false;
            }
        }

        return true;
    }

    private function throwIfResultFailed(Response $response): void
    {
        $decoded = $response->json();
        $result = $decoded['Result'] ?? null;

        if (! is_array($result) || ! array_key_exists('ResultCode', $result)) {
            return;
        }

        $code = trim((string) $result['ResultCode']);

        if ($code === '' || strcasecmp($code, 'OK') === 0) {
            return;
        }

        throw new ApiException(
            $this->extractMessage($decoded, $response->body(), $response->statusCode()),
            $response->statusCode(),
            $decoded,
            $response->body(),
        );
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
            $description = trim((string) $decoded['Result']['ResultDescription']);

            if ($description !== '') {
                return $description;
            }
        }

        foreach (['Message', 'error_description', 'error'] as $key) {
            if (! isset($decoded[$key])) {
                continue;
            }

            $value = trim((string) $decoded[$key]);

            if ($value !== '') {
                return $value;
            }
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
