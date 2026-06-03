<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot;

use Yannelli\DoseSpot\Exceptions\DoseSpotException;

final class Config
{
    public readonly string $baseUrl;

    public function __construct(
        public readonly string $clinicId,
        public readonly string $clinicKey,
        public readonly Environment $environment = Environment::Production,
        public readonly ?int $userId = null,
        public readonly int $timeout = 30,
        public readonly int $connectTimeout = 10,
        ?string $baseUrl = null,
    ) {
        if ($clinicId === '' || $clinicKey === '') {
            throw new DoseSpotException('Clinic ID and Clinic Key are required.');
        }

        if ($timeout < 1 || $connectTimeout < 1) {
            throw new DoseSpotException('timeout and connectTimeout must be positive.');
        }

        $this->baseUrl = rtrim($baseUrl ?? $environment->baseUrl(), '/');
    }

    public function tokenUrl(): string
    {
        return $this->baseUrl.'/token';
    }

    public function apiUrl(string $path): string
    {
        return $this->baseUrl.'/'.ltrim($path, '/');
    }

    public function withUserId(int $userId): self
    {
        return new self(
            clinicId: $this->clinicId,
            clinicKey: $this->clinicKey,
            environment: $this->environment,
            userId: $userId,
            timeout: $this->timeout,
            connectTimeout: $this->connectTimeout,
            baseUrl: $this->baseUrl,
        );
    }
}
