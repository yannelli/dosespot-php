<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Refills extends Resource
{
    /**
     * GET /api/notifications/refills/clinician
     * GET /api/notifications/refills/clinician/{onBehalfOf}
     */
    public function forClinician(?int $onBehalfOf = null): array
    {
        $path = 'api/notifications/refills/clinician';

        if ($onBehalfOf !== null) {
            $path .= "/{$onBehalfOf}";
        }

        return $this->get($path);
    }

    /**
     * GET /api/notifications/refills/clinic
     * GET /api/notifications/refills/clinic/{onBehalfOf}
     */
    public function forClinic(?int $onBehalfOf = null): array
    {
        $path = 'api/notifications/refills/clinic';

        if ($onBehalfOf !== null) {
            $path .= "/{$onBehalfOf}";
        }

        return $this->get($path);
    }

    /**
     * GET /api/notifications/refills/patients/{patientId}
     * GET /api/notifications/refills/patients/{patientId}/{onBehalfOf}
     */
    public function forPatient(int $patientId, ?int $onBehalfOf = null): array
    {
        $path = "api/notifications/refills/patients/{$patientId}";

        if ($onBehalfOf !== null) {
            $path .= "/{$onBehalfOf}";
        }

        return $this->get($path);
    }

    /**
     * POST /api/notifications/refills/{refillId}/approve
     */
    public function approve(int $refillId, array $payload = []): array
    {
        return $this->post("api/notifications/refills/{$refillId}/approve", $payload);
    }

    /**
     * POST /api/notifications/refills/{refillId}/approveOnBehalfOf/{onBehalfOf}
     */
    public function approveOnBehalfOf(int $refillId, int $onBehalfOf, array $payload = []): array
    {
        return $this->post(
            "api/notifications/refills/{$refillId}/approveOnBehalfOf/{$onBehalfOf}",
            $payload,
        );
    }

    /**
     * POST /api/notifications/refills/{refillId}/deny
     */
    public function deny(int $refillId, array $payload = []): array
    {
        return $this->post("api/notifications/refills/{$refillId}/deny", $payload);
    }

    /**
     * POST /api/notifications/refills/{refillId}/denyOnBehalfOf/{onBehalfOf}
     */
    public function denyOnBehalfOf(int $refillId, int $onBehalfOf, array $payload = []): array
    {
        return $this->post(
            "api/notifications/refills/{$refillId}/denyOnBehalfOf/{$onBehalfOf}",
            $payload,
        );
    }

    /**
     * POST /api/notifications/refills/{refillId}/changePatient
     */
    public function changePatient(int $refillId, int $patientId): array
    {
        return $this->post(
            "api/notifications/refills/{$refillId}/changePatient",
            null,
            ['patientId' => $patientId],
        );
    }

    /**
     * POST /api/notifications/refills/{refillId}/changePatientOnBehalfOf/{onBehalfOf}/{patientId}
     */
    public function changePatientOnBehalfOf(int $refillId, int $onBehalfOf, int $patientId): array
    {
        return $this->post(
            "api/notifications/refills/{$refillId}/changePatientOnBehalfOf/{$onBehalfOf}/{$patientId}",
        );
    }

    /**
     * POST /api/notifications/refills/{refillId}/replace
     */
    public function replace(int $refillId, array $payload): array
    {
        return $this->post("api/notifications/refills/{$refillId}/replace", $payload);
    }
}
