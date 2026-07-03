<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Exceptions;

use Throwable;

class ApiException extends DoseSpotException
{
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
        public readonly ?array $responseBody = null,
        public readonly ?string $rawResponse = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function responseBody(): ?array
    {
        return $this->responseBody;
    }

    public function rawResponse(): ?string
    {
        return $this->rawResponse;
    }
}
