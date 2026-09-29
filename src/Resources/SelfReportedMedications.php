<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class SelfReportedMedications extends Resource
{
    /**
     * GET /api/patients/{patientId}/selfReportedMedications/{selfReportedMedicationId}
     *
     * Get patient's self-reported medication details by SelfReportedMedicationId.
     *
     * @dosespot SelfReportedMedications_GetPatientSelfReportMedicationDetailsByIdV2
     */
    public function find(int $patientId, int $selfReportedMedicationId): array
    {
        return $this->get("api/patients/{$patientId}/selfReportedMedications/{$selfReportedMedicationId}");
    }

    /**
     * GET /api/patients/{patientId}/selfReportedMedications
     *
     * Get patient's self-reported medications.
     *
     * @dosespot SelfReportedMedications_GetPatientSelfReportMedicationsV2
     */
    public function forPatient(
        int $patientId,
        \DateTimeInterface|string|null $startDate = null,
        \DateTimeInterface|string|null $endDate = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get("api/patients/{$patientId}/selfReportedMedications", [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/coded
     *
     * Add a coded self-reported medication.
     *
     * @dosespot SelfReportedMedications_AddSelfReportedMedicationCodedV2
     */
    public function createCoded(int $patientId, array $medication): array
    {
        return $this->post("api/patients/{$patientId}/selfReportedMedications/coded", $medication);
    }

    /**
     * PUT /api/patients/{patientId}/selfReportedMedications/coded/{selfReportedMedicationId}
     *
     * Edit a coded self-reported medication.
     *
     * @dosespot SelfReportedMedications_EditSelfReportedMedicationCodedV2
     */
    public function updateCoded(int $patientId, int $selfReportedMedicationId, array $medication): array
    {
        return $this->put("api/patients/{$patientId}/selfReportedMedications/coded/{$selfReportedMedicationId}", $medication);
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/simple
     *
     * Add a simple self-reported medication.
     *
     * @dosespot SelfReportedMedications_AddSelfReportedMedicationSimpleV2
     */
    public function createSimple(int $patientId, array $medication): array
    {
        return $this->post("api/patients/{$patientId}/selfReportedMedications/simple", $medication);
    }

    /**
     * PUT /api/patients/{patientId}/selfReportedMedications/simple/{selfReportedMedicationId}
     *
     * Edit a simple self-reported medication.
     *
     * @dosespot SelfReportedMedications_EditSelfReportedMedicationSimpleV2
     */
    public function updateSimple(int $patientId, int $selfReportedMedicationId, array $medication): array
    {
        return $this->put("api/patients/{$patientId}/selfReportedMedications/simple/{$selfReportedMedicationId}", $medication);
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/freetext
     *
     * Add a free-text self-reported medication.
     *
     * @dosespot SelfReportedMedications_AddSelfReportedMedicationFreeTextV2
     */
    public function createFreetext(int $patientId, array $medication): array
    {
        return $this->post("api/patients/{$patientId}/selfReportedMedications/freetext", $medication);
    }

    /**
     * PUT /api/patients/{patientId}/selfReportedMedications/freetext/{selfReportedMedicationId}
     *
     * Edit a free-text self-reported medication.
     *
     * @dosespot SelfReportedMedications_EditSelfReportedMedicationFreeTextV2
     */
    public function updateFreetext(int $patientId, int $selfReportedMedicationId, array $medication): array
    {
        return $this->put("api/patients/{$patientId}/selfReportedMedications/freetext/{$selfReportedMedicationId}", $medication);
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/{selfReportedMedicationId}/updateStatus
     *
     * Update self-reported medication status.
     *
     * @dosespot SelfReportedMedications_UpdateSelfReportedMedicationStatusV2
     */
    public function updateStatus(int $patientId, int $selfReportedMedicationId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/selfReportedMedications/{$selfReportedMedicationId}/updateStatus", $payload);
    }
}
