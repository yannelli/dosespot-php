<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Refills extends Resource
{
    /**
     * GET /api/refills/pending
     *
     * Get refill requests for clinic(s)/patient.
     * clinic is All or Current.
     *
     * @dosespot Refills_GetRefillRequestDetailsV2
     */
    public function pending(
        \BackedEnum|string|null $clinic = null,
        ?int $patientId = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get('api/refills/pending', [
            'clinic' => $clinic,
            'patientId' => $patientId,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * GET /api/refills/pending/detailed
     *
     * Get detailed refill requests for clinic(s)/patient.
     * clinic is All or Current.
     *
     * @dosespot Refills_GetDetailedRefillRequestsV2
     */
    public function pendingDetailed(
        \BackedEnum|string|null $clinic = null,
        ?int $patientId = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get('api/refills/pending/detailed', [
            'clinic' => $clinic,
            'patientId' => $patientId,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * POST /api/refills/{refillId}/approve
     *
     * Approves a pending refill request.
     *
     * @dosespot Refills_ApproveRefillV2
     */
    public function approve(int $refillId, array $payload): array
    {
        return $this->post("api/refills/{$refillId}/approve", $payload);
    }

    /**
     * POST /api/refills/{refillId}/deny
     *
     * Denies a pending refill request.
     *
     * @dosespot Refills_DenyRefillV2
     */
    public function deny(int $refillId, array $payload): array
    {
        return $this->post("api/refills/{$refillId}/deny", $payload);
    }

    /**
     * POST /api/refills/{refillId}/replace
     *
     * Replace the refill request with new prescription details.
     *
     * @dosespot Refills_ReplaceRefillV2
     */
    public function replace(int $refillId, array $payload): array
    {
        return $this->post("api/refills/{$refillId}/replace", $payload);
    }

    /**
     * PATCH /api/refills/{refillId}/changePatient
     *
     * Change the patient details on a refill request.
     *
     * @dosespot Refills_ChangeRefillRequestPatientV2
     */
    public function changePatient(int $refillId, int $patientId): array
    {
        return $this->patch("api/refills/{$refillId}/changePatient", [
            'PatientId' => $patientId,
        ]);
    }
}
