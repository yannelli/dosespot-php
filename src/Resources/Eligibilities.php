<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Eligibilities extends Resource
{
    /**
     * GET /api/patients/{patientId}/eligibilities
     */
    public function forPatient(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/eligibilities");
    }

    /**
     * GET /api/patients/{patientId}/therapeuticAlternatives
     */
    public function therapeuticAlternatives(int $patientId, int $patientEligibilityId, string $ndc): array
    {
        return $this->get("api/patients/{$patientId}/therapeuticAlternatives", [
            'patientEligibilityId' => $patientEligibilityId,
            'ndc' => $ndc,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/formulary
     */
    public function formulary(int $patientId, int $patientEligibilityId, string $ndc): array
    {
        return $this->get("api/patients/{$patientId}/formulary", [
            'patientEligibilityId' => $patientEligibilityId,
            'ndc' => $ndc,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/prescriptionbenefits
     */
    public function prescriptionBenefits(
        int $patientId,
        string $ndc,
        int $pharmacyId,
        float|int $quantity,
        int $daysSupply,
        int $dispenseUnitTypeId,
        int $patientEligibilityId,
    ): array {
        return $this->get("api/patients/{$patientId}/prescriptionbenefits", [
            'ndc' => $ndc,
            'pharmacyId' => $pharmacyId,
            'quantity' => $quantity,
            'daysSupply' => $daysSupply,
            'dispenseUnitTypeID' => $dispenseUnitTypeId,
            'patientEligibilityId' => $patientEligibilityId,
        ]);
    }
}
