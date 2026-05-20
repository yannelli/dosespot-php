<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Exceptions;

class ApiException extends DoseSpotException
{
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
        public readonly ?array $responseBody = null,
        public readonly ?string $rawResponse = null,
    ) {
        parent::__construct($message, $statusCode);
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
