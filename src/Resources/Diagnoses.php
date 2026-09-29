<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Diagnoses extends Resource
{
    /**
     * GET /api/diagnoses/search
     *
     * Search Diagnoses.
     *
     * @dosespot Diagnoses_DiagnosisSearchV2
     */
    public function search(string $searchTerm, ?int $pageNumber = null): array
    {
        return $this->get('api/diagnoses/search', [
            'searchTerm' => $searchTerm,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/diagnoses/search
     *
     * Search patient diagnoses.
     *
     * @dosespot PatientDiagnoses_SearchPatientDiagnosesV2
     */
    public function searchForPatient(int $patientId, string $searchTerm, ?int $pageNumber = null): array
    {
        return $this->get("api/patients/{$patientId}/diagnoses/search", [
            'searchTerm' => $searchTerm,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/diagnoses
     *
     * Get patient diagnoses.
     *
     * @dosespot PatientDiagnoses_GetPatientDiagnosesV2
     */
    public function forPatient(int $patientId, ?bool $includeExpired = null, ?int $pageNumber = null): array
    {
        return $this->get("api/patients/{$patientId}/diagnoses", [
            'includeExpired' => $includeExpired,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * POST /api/patients/{patientId}/diagnoses
     *
     * Add patient diagnosis.
     *
     * @dosespot PatientDiagnoses_AddPatientDiagnosisV2
     */
    public function create(int $patientId, array $diagnosis): array
    {
        return $this->post("api/patients/{$patientId}/diagnoses", $diagnosis);
    }

    /**
     * PUT /api/patients/{patientId}/diagnoses/{patientDiagnosisId}
     *
     * Edit patient diagnosis.
     *
     * @dosespot PatientDiagnoses_EditPatientDiagnosisV2
     */
    public function update(int $patientId, int $patientDiagnosisId, array $diagnosis): array
    {
        return $this->put("api/patients/{$patientId}/diagnoses/{$patientDiagnosisId}", $diagnosis);
    }

    /**
     * DELETE /api/patients/{patientId}/diagnoses/{patientDiagnosisId}
     *
     * Delete patient diagnosis.
     *
     * @dosespot PatientDiagnoses_DeletePatientDiagnosisV2
     */
    public function deleteDiagnosis(int $patientId, int $patientDiagnosisId): array
    {
        return $this->delete("api/patients/{$patientId}/diagnoses/{$patientDiagnosisId}");
    }
}
