<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class SelfReportedMedications extends Resource
{
    /**
     * POST /api/patients/{patientId}/selfReportedMedications/coded
     */
    public function createCoded(int $patientId, array $medication): array
    {
        return $this->post("api/patients/{$patientId}/selfReportedMedications/coded", $medication);
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/coded/{selfReportedMedicationId}
     */
    public function updateCoded(int $patientId, int $selfReportedMedicationId, array $medication): array
    {
        return $this->post(
            "api/patients/{$patientId}/selfReportedMedications/coded/{$selfReportedMedicationId}",
            $medication,
        );
    }

    /**
     * PUT /api/patients/{patientId}/selfReportedMedications/coded/{selfReportedMedicationId}
     */
    public function replaceCoded(int $patientId, int $selfReportedMedicationId, array $medication): array
    {
        return $this->put(
            "api/patients/{$patientId}/selfReportedMedications/coded/{$selfReportedMedicationId}",
            $medication,
        );
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/simple
     */
    public function createSimple(int $patientId, array $medication): array
    {
        return $this->post("api/patients/{$patientId}/selfReportedMedications/simple", $medication);
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/simple/{selfReportedMedicationId}
     */
    public function updateSimple(int $patientId, int $selfReportedMedicationId, array $medication): array
    {
        return $this->post(
            "api/patients/{$patientId}/selfReportedMedications/simple/{$selfReportedMedicationId}",
            $medication,
        );
    }

    /**
     * PUT /api/patients/{patientId}/selfReportedMedications/simple/{selfReportedMedicationId}
     */
    public function replaceSimple(int $patientId, int $selfReportedMedicationId, array $medication): array
    {
        return $this->put(
            "api/patients/{$patientId}/selfReportedMedications/simple/{$selfReportedMedicationId}",
            $medication,
        );
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/freetext
     */
    public function createFreetext(int $patientId, array $medication): array
    {
        return $this->post("api/patients/{$patientId}/selfReportedMedications/freetext", $medication);
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/freetext/{selfReportedMedicationId}
     */
    public function updateFreetext(int $patientId, int $selfReportedMedicationId, array $medication): array
    {
        return $this->post(
            "api/patients/{$patientId}/selfReportedMedications/freetext/{$selfReportedMedicationId}",
            $medication,
        );
    }

    /**
     * PUT /api/patients/{patientId}/selfReportedMedications/freetext/{selfReportedMedicationId}
     */
    public function replaceFreetext(int $patientId, int $selfReportedMedicationId, array $medication): array
    {
        return $this->put(
            "api/patients/{$patientId}/selfReportedMedications/freetext/{$selfReportedMedicationId}",
            $medication,
        );
    }

    /**
     * POST /api/patients/{patientId}/selfReportedMedications/{selfReportedMedicationId}/updateStatus
     */
    public function updateStatus(int $patientId, int $selfReportedMedicationId, array $payload): array
    {
        return $this->post(
            "api/patients/{$patientId}/selfReportedMedications/{$selfReportedMedicationId}/updateStatus",
            $payload,
        );
    }
}
