<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Exceptions;

class RateLimitException extends ApiException
{
    public function __construct(
        string $message,
        int $statusCode = 429,
        ?array $responseBody = null,
        ?string $rawResponse = null,
        public readonly ?int $retryAfter = null,
    ) {
        parent::__construct($message, $statusCode, $responseBody, $rawResponse);
    }

    public function retryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
