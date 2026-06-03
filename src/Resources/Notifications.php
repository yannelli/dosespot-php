<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Notifications extends Resource
{
    /**
     * GET /api/notifications/counts
     */
    public function counts(): array
    {
        return $this->get('api/notifications/counts');
    }

    /**
     * GET /api/notifications/batchCounts
     *
     * @param  list<int>  $clinicianIds
     */
    public function batchCounts(array $clinicianIds): array
    {
        return $this->get('api/notifications/batchCounts', [
            'clinicianId' => $clinicianIds,
        ]);
    }

    /**
     * GET /api/notifications/errors
     */
    public function errors(): array
    {
        return $this->get('api/notifications/errors');
    }

    /**
     * GET /api/notifications/errorsByClient
     */
    public function errorsByClient(
        \DateTimeInterface|string|null $startDate = null,
        \DateTimeInterface|string|null $endDate = null,
    ): array {
        return $this->get('api/notifications/errorsByClient', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
