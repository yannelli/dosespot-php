<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Clinics extends Resource
{
    /**
     * GET /api/clinics/{clinicId}
     *
     * Gets clinic information.
     *
     * @dosespot Clinics_GetClinicDetailsV2
     */
    public function find(int $clinicId): array
    {
        return $this->get("api/clinics/{$clinicId}");
    }

    /**
     * POST /api/clinics
     *
     * Add clinic.
     *
     * @dosespot Clinics_ClinicAddV2
     */
    public function create(array $clinic): array
    {
        return $this->post('api/clinics', $clinic);
    }

    /**
     * PUT /api/clinics/{clinicId}
     *
     * Edit Clinic information.
     *
     * @dosespot Clinics_ClinicEditV2
     */
    public function update(int $clinicId, array $clinic): array
    {
        return $this->put("api/clinics/{$clinicId}", $clinic);
    }

    /**
     * GET /api/patients/{patientId}/clinics
     *
     * Get patient's clinics.
     *
     * @dosespot Clinics_GetPatientClinicsV2
     */
    public function forPatient(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/clinics");
    }

    /**
     * GET /api/clinicians/{clinicianId}/clinicIds
     *
     * Gets a clinician's clinic identifiers.
     * clinicStatus is Active, Inactive, or All.
     *
     * @dosespot Clinics_GetClinicianClinicsV2
     */
    public function forClinician(
        int $clinicianId,
        ?bool $includeClinicGroups = null,
        \BackedEnum|string|null $clinicStatus = null,
    ): array {
        return $this->get("api/clinicians/{$clinicianId}/clinicIds", [
            'includeClinicGroups' => $includeClinicGroups,
            'clinicStatus' => $clinicStatus,
        ]);
    }

    /**
     * POST /api/patients/{patientId}/transfer
     *
     * Transfer patient clinic.
     *
     * @dosespot Clinics_PatientClinicTransferV2
     */
    public function transferPatient(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/transfer", $payload);
    }

    /**
     * POST /api/clinicians/{clinicianId}/clinics
     *
     * Add clinician clinics.
     *
     * @dosespot Clinics_ClinicianAddMultipleClinicsV2
     */
    public function addClinics(int $clinicianId, array $payload): array
    {
        return $this->post("api/clinicians/{$clinicianId}/clinics", $payload);
    }

    /**
     * DELETE /api/clinics/{clinicId}/clinicians
     *
     * Removes clinician(s) from a specified clinic.
     *
     * @dosespot Clinics_ClinicRemoveMultipleCliniciansV2
     */
    public function removeClinicians(int $clinicId, array $payload): array
    {
        return $this->delete("api/clinics/{$clinicId}/clinicians", body: $payload);
    }

    /**
     * POST /api/clinicGroups
     *
     * Manages clinic groups.
     *
     * @dosespot ClinicGroups_AddEditClinicGroupV2
     */
    public function saveGroup(array $group): array
    {
        return $this->post('api/clinicGroups', $group);
    }
}
