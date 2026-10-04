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
        public readonly string $subscriptionKey,
        public readonly int $userId,
        public readonly Environment $environment = Environment::Production,
        public readonly int $timeout = 30,
        public readonly int $connectTimeout = 10,
        ?string $baseUrl = null,
    ) {
        if ($clinicId === '' || $clinicKey === '' || $subscriptionKey === '') {
            throw new DoseSpotException('Clinic ID, clinic key, and subscription key are required.');
        }

        if ($userId < 1) {
            throw new DoseSpotException('userId must be a positive DoseSpot clinician id.');
        }

        if ($timeout < 1 || $connectTimeout < 1) {
            throw new DoseSpotException('timeout and connectTimeout must be positive.');
        }

        $this->baseUrl = rtrim($baseUrl ?? $environment->baseUrl(), '/');
    }

    public function tokenUrl(): string
    {
        return $this->baseUrl.'/connect/token';
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
            subscriptionKey: $this->subscriptionKey,
            userId: $userId,
            environment: $this->environment,
            timeout: $this->timeout,
            connectTimeout: $this->connectTimeout,
            baseUrl: $this->baseUrl,
        );
    }
}
