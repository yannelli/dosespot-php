<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Transparency extends Resource
{
    /**
     * GET /api/patients/{patientId}/transparency/alternativePharmacies
     *
     * Returns alternative pharmacies and offers for a given NDC and patient.
     *
     * @dosespot Transparency_AlternativePharmaciesV2
     */
    public function alternativePharmacies(int $patientId, string $ndc): array
    {
        return $this->get("api/patients/{$patientId}/transparency/alternativePharmacies", [
            'nDC' => $ndc,
        ]);
    }
}
