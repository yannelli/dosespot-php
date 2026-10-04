<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Interactions extends Resource
{
    /**
     * GET /api/patients/{patientId}/interactions/drugs
     *
     * Check drug-drug interactions.
     *
     * @dosespot Interactions_GetDrugInteractionsV2
     */
    public function drugs(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/interactions/drugs");
    }

    /**
     * GET /api/patients/{patientId}/interactions/allergies
     *
     * Get allergies interactions.
     *
     * @dosespot Interactions_GetAllergyInteractionsV2
     */
    public function allergies(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/interactions/allergies");
    }

    /**
     * GET /api/patients/{patientId}/interactions/precheck/{dispensableDrugId}
     *
     * Check pre-check interactions.
     *
     * @dosespot Interactions_GetPreCheckInteractionsV2
     */
    public function precheck(int $patientId, int $dispensableDrugId): array
    {
        return $this->get("api/patients/{$patientId}/interactions/precheck/{$dispensableDrugId}");
    }
}
