<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Http;

use Psr\Http\Message\ResponseInterface;

final class Response
{
    private ?array $decoded = null;

    public function __construct(public readonly ResponseInterface $raw)
    {
    }

    public function statusCode(): int
    {
        return $this->raw->getStatusCode();
    }

    public function body(): string
    {
        return (string) $this->raw->getBody();
    }

    public function json(): array
    {
        if ($this->decoded !== null) {
            return $this->decoded;
        }

        $body = $this->body();

        if ($body === '') {
            return $this->decoded = [];
        }

        $decoded = json_decode($body, true);

        return $this->decoded = is_array($decoded) ? $decoded : [];
    }

    public function header(string $name): ?string
    {
        $header = $this->raw->getHeader($name);

        return $header === [] ? null : $header[0];
    }

    public function isSuccessful(): bool
    {
        $status = $this->statusCode();

        return $status >= 200 && $status < 300;
    }
}
