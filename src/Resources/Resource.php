<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

use Yannelli\DoseSpot\Http\HttpClient;
use Yannelli\DoseSpot\Http\Response;

abstract class Resource
{
    public function __construct(protected readonly HttpClient $client)
    {
    }

    protected function get(string $path, array $query = []): array
    {
        return $this->client->get($path, $query)->json();
    }

    protected function post(string $path, ?array $body = null, array $query = []): array
    {
        return $this->client->post($path, $body, $query)->json();
    }

    protected function put(string $path, ?array $body = null, array $query = []): array
    {
        return $this->client->put($path, $body, $query)->json();
    }

    protected function patch(string $path, ?array $body = null, array $query = []): array
    {
        return $this->client->patch($path, $body, $query)->json();
    }

    protected function delete(string $path, array $query = [], ?array $body = null): array
    {
        return $this->client->delete($path, $query, $body)->json();
    }

    protected function raw(string $method, string $path, ?array $body = null, array $query = []): Response
    {
        return $this->client->request($method, $path, $body, $query);
    }
}
