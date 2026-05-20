<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Auth;

class AccessToken
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
        return ucfirst($this->tokenType) . ' ' . $this->token;
    }
}
