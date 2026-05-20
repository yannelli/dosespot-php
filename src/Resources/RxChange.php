<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class RxChange extends Resource
{
    /**
     * GET /api/notifications/rxchange/clinician
     */
    public function forClinician(): array
    {
        return $this->get('api/notifications/rxchange/clinician');
    }

    /**
     * GET /api/notifications/rxchange/clinic
     */
    public function forClinic(): array
    {
        return $this->get('api/notifications/rxchange/clinic');
    }

    /**
     * GET /api/notifications/rxchange/client
     */
    public function forClient(): array
    {
        return $this->get('api/notifications/rxchange/client');
    }

    /**
     * GET /api/notifications/rxchange/patients/{patientId}
     */
    public function forPatient(int $patientId): array
    {
        return $this->get("api/notifications/rxchange/patients/{$patientId}");
    }

    /**
     * POST /api/notifications/rxchange/patients/{patientId}/{rxChangeId}/reconcile
     */
    public function reconcile(int $patientId, int $rxChangeId, array $payload = []): array
    {
        return $this->post(
            "api/notifications/rxchange/patients/{$patientId}/{$rxChangeId}/reconcile",
            $payload,
        );
    }

    /**
     * POST /api/notifications/rxchange/patients/{patientId}/{rxChangeId}/reconcileOnBehalfOf/{onBehalfOf}
     */
    public function reconcileOnBehalfOf(
        int $patientId,
        int $rxChangeId,
        int $onBehalfOf,
        array $payload = [],
    ): array {
        return $this->post(
            "api/notifications/rxchange/patients/{$patientId}/{$rxChangeId}/reconcileOnBehalfOf/{$onBehalfOf}",
            $payload,
        );
    }

    /**
     * POST /api/notifications/rxchange/{rxChangeId}/changePatient
     */
    public function changePatient(int $rxChangeId, array $payload): array
    {
        return $this->post("api/notifications/rxchange/{$rxChangeId}/changePatient", $payload);
    }

    /**
     * POST /api/notifications/rxchange/{rxChangeId}/changePatientOnBehalfOf/{patientId}/{onBehalfOf}
     */
    public function changePatientOnBehalfOf(int $rxChangeId, int $patientId, int $onBehalfOf): array
    {
        return $this->post(
            "api/notifications/rxchange/{$rxChangeId}/changePatientOnBehalfOf/{$patientId}/{$onBehalfOf}",
        );
    }

    /**
     * POST /api/notifications/rxchange/{rxChangeId}/approve
     */
    public function approve(int $rxChangeId, array $payload = []): array
    {
        return $this->post("api/notifications/rxchange/{$rxChangeId}/approve", $payload);
    }

    /**
     * POST /api/notifications/rxchange/{rxChangeId}/approveOnBehalfOf/{onBehalfOf}
     */
    public function approveOnBehalfOf(int $rxChangeId, int $onBehalfOf, array $payload = []): array
    {
        return $this->post(
            "api/notifications/rxchange/{$rxChangeId}/approveOnBehalfOf/{$onBehalfOf}",
            $payload,
        );
    }

    /**
     * POST /api/notifications/rxchange/{rxChangeId}/deny
     */
    public function deny(int $rxChangeId, array $payload = []): array
    {
        return $this->post("api/notifications/rxchange/{$rxChangeId}/deny", $payload);
    }

    /**
     * POST /api/notifications/rxchange/{rxChangeId}/denyOnBehalfOf/{onBehalfOf}
     */
    public function denyOnBehalfOf(int $rxChangeId, int $onBehalfOf, array $payload = []): array
    {
        return $this->post(
            "api/notifications/rxchange/{$rxChangeId}/denyOnBehalfOf/{$onBehalfOf}",
            $payload,
        );
    }
}
