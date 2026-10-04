<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Eligibilities extends Resource
{
    /**
     * GET /api/patients/{patientId}/eligibilities
     *
     * Get patient's insurance information.
     *
     * @dosespot Eligibilities_GetPayerInformationV2
     */
    public function forPatient(int $patientId, ?int $clinicId = null): array
    {
        return $this->get("api/patients/{$patientId}/eligibilities", [
            'clinicId' => $clinicId,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/formulary
     *
     * Get patient's medication coverage.
     *
     * @dosespot Eligibilities_GetMedicationCoverageV2
     */
    public function formulary(int $patientId, int $patientEligibilityId, string $ndc): array
    {
        return $this->get("api/patients/{$patientId}/formulary", [
            'patientEligibilityId' => $patientEligibilityId,
            'nDC' => $ndc,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/prescriptionbenefits
     *
     * Get prescription benefits.
     *
     * @dosespot Eligibilities_GetPrescriptionBenefitsV2
     */
    public function prescriptionBenefits(
        int $patientId,
        string $ndc,
        int $pharmacyId,
        float|int $quantity,
        int $daysSupply,
        int $dispenseUnitTypeId,
        ?int $patientEligibilityId = null,
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

    /**
     * POST /api/patients/{patientId}/insurance
     *
     * Add custom insurance info.
     *
     * @dosespot Eligibilities_CustomInsuranceInfoV2
     */
    public function setInsurance(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/insurance", $payload);
    }
}
