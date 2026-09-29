<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Allergies extends Resource
{
    /**
     * GET /api/patients/{patientId}/allergies
     *
     * Get patient's allergies.
     *
     * @dosespot Allergies_GetPatientAllergiesV2
     */
    public function forPatient(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/allergies");
    }

    /**
     * PUT /api/patients/{patientId}/allergies/{patientAllergyId}
     *
     * Edit drug allergy.
     *
     * @dosespot Allergies_EditPatientAllergyV2
     */
    public function update(int $patientId, int $patientAllergyId, array $allergy): array
    {
        return $this->put("api/patients/{$patientId}/allergies/{$patientAllergyId}", $allergy);
    }

    /**
     * POST /api/patients/{patientId}/allergies/coded
     *
     * Add coded patient allergy.
     *
     * @dosespot Allergies_AddCodedPatientAllergyV2
     */
    public function createCoded(int $patientId, array $allergy): array
    {
        return $this->post("api/patients/{$patientId}/allergies/coded", $allergy);
    }

    /**
     * POST /api/patients/{patientId}/allergies/freetext
     *
     * Add freetext patient allergy.
     *
     * @dosespot Allergies_AddFreetextPatientAllergyV2
     */
    public function createFreetext(int $patientId, array $allergy): array
    {
        return $this->post("api/patients/{$patientId}/allergies/freetext", $allergy);
    }

    /**
     * POST /api/patients/{patientId}/allergies/noKnownAllergy
     *
     * Add no known patient allergy record.
     *
     * @dosespot Allergies_AddNoKnownPatientAllergyV2
     */
    public function createNoKnown(int $patientId): array
    {
        return $this->post("api/patients/{$patientId}/allergies/noKnownAllergy");
    }
}
