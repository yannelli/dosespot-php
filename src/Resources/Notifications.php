<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Notifications extends Resource
{
    /**
     * GET /api/notifications/counts
     *
     * Get Notification Counts For Current Clinician.
     *
     * @dosespot Notifications_GetPrescriberNotificationCountsV2
     */
    public function counts(): array
    {
        return $this->get('api/notifications/counts');
    }

    /**
     * GET /api/notifications/errors
     *
     * Get Transmission Errors For Current Clinician.
     * clinic is All or Current.
     *
     * @dosespot Notifications_GetTransmissionErrorDetailsV2
     */
    public function errors(?int $pageNumber = null, \BackedEnum|string|null $clinic = null): array
    {
        return $this->get('api/notifications/errors', [
            'pageNumber' => $pageNumber,
            'clinic' => $clinic,
        ]);
    }
}
