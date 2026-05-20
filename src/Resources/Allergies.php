<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Allergies extends Resource
{
    /**
     * GET /api/allergies/search
     *
     * Search the allergen database by free-text query.
     */
    public function search(string $query): array
    {
        return $this->get('api/allergies/search', ['q' => $query]);
    }

    /**
     * GET /api/patients/{patientId}/allergies
     *
     * List all allergies for a patient.
     */
    public function forPatient(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/allergies");
    }

    /**
     * POST /api/patients/{patientId}/allergies
     *
     * Record a new allergy for a patient.
     */
    public function create(int $patientId, array $allergy): array
    {
        return $this->post("api/patients/{$patientId}/allergies", $allergy);
    }

    /**
     * PUT /api/patients/{patientId}/allergies/{patientAllergyId}
     *
     * Replace an existing allergy record.
     */
    public function replace(int $patientId, int $patientAllergyId, array $allergy): array
    {
        return $this->put("api/patients/{$patientId}/allergies/{$patientAllergyId}", $allergy);
    }

    /**
     * POST /api/patients/{patientId}/allergies/{patientAllergyId}
     *
     * Update specific fields on a patient's allergy.
     */
    public function update(int $patientId, int $patientAllergyId, array $allergy): array
    {
        return $this->post("api/patients/{$patientId}/allergies/{$patientAllergyId}", $allergy);
    }

    /**
     * GET /api/patients/{patientId}/allergies/interactions
     *
     * Retrieve allergy-medication interactions for a patient.
     */
    public function interactions(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/allergies/interactions");
    }
}
