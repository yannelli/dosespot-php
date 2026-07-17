<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Auth;

final class AccessToken
{
    public function __construct(
        public readonly string $token,
        public readonly string $tokenType,
        public readonly int $expiresAt,
        public readonly ?int $userId = null,
    ) {
    }

    public function isExpired(int $leewaySeconds = 30): bool
    {
        return time() >= ($this->expiresAt - $leewaySeconds);
    }

    public function authorizationHeader(): string
    {
        // OAuth token_type is case-insensitive. Normalize second+ chars so values
        // like "BEARER" become the expected "Bearer " scheme.
        return ucfirst(strtolower($this->tokenType)).' '.$this->token;
    }
}
