<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class MedicationHistory extends Resource
{
    /**
     * GET /api/patients/{patientId}/medications/history
     *
     * Get patient's medication history.
     *
     * @dosespot MedicationHistory_GetMedicationHistoryV2
     */
    public function forPatient(
        int $patientId,
        \DateTimeInterface|string|null $start = null,
        \DateTimeInterface|string|null $end = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get("api/patients/{$patientId}/medications/history", [
            'start' => $start,
            'end' => $end,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * POST /api/patients/{patientId}/medications/history/consent
     *
     * Log patient's medication history consent.
     *
     * @dosespot MedicationHistory_LogPatientMedicationHistoryConsentV2
     */
    public function logConsent(int $patientId): array
    {
        return $this->post("api/patients/{$patientId}/medications/history/consent");
    }
}
