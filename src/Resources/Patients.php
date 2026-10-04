<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Patients extends Resource
{
    /**
     * GET /api/patients/search
     *
     * Search Patients. Status is InactiveOnly, ActiveOnly, or Both.
     *
     * @dosespot Patients_SearchPatientsV2
     */
    public function search(
        ?string $firstName = null,
        ?string $lastName = null,
        \DateTimeInterface|string|null $dob = null,
        \BackedEnum|string|null $status = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get('api/patients/search', [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'dob' => $dob,
            'status' => $status,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * GET /api/patients/{patientId}
     *
     * Get patient demographics.
     *
     * @dosespot Patients_GetPatientDemographicDataV2
     */
    public function find(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}");
    }

    /**
     * POST /api/patients
     *
     * Add Patient.
     *
     * @dosespot Patients_AddPatientV2
     */
    public function create(array $patient): array
    {
        return $this->post('api/patients', $patient);
    }

    /**
     * PUT /api/patients/{patientId}
     *
     * Edit patient demographics.
     *
     * @dosespot Patients_EditPatientDemographicDataV2
     */
    public function update(int $patientId, array $patient): array
    {
        return $this->put("api/patients/{$patientId}", $patient);
    }

    /**
     * PUT /api/patients/{patientId}/SSN
     *
     * Add/Update Patient SSN details.
     *
     * @dosespot Patients_AddEditPatientSsnV2
     */
    public function setSsn(int $patientId, string $ssn): array
    {
        return $this->put("api/patients/{$patientId}/SSN", [
            'PatientSSN' => $ssn,
        ]);
    }

    /**
     * DELETE /api/patients/{patientId}/SSN
     *
     * Delete patient's SSN.
     *
     * @dosespot Patients_DeletePatientSSNV2
     */
    public function deleteSsn(int $patientId): array
    {
        return $this->delete("api/patients/{$patientId}/SSN");
    }

    /**
     * POST /api/patients/merge
     *
     * Merge patient's medications and allergies.
     *
     * @dosespot Patients_MergePatientsV2
     */
    public function merge(array $payload): array
    {
        return $this->post('api/patients/merge', $payload);
    }
}
