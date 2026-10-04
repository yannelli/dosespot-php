<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class ClinicFavorites extends Resource
{
    /**
     * GET /api/clinics/{clinicId}/prescriptionFavorites
     *
     * Search for clinic prescription favorites.
     * favoriteType is All, Medication, Supply, or CompiledCompound.
     *
     * @dosespot ClinicFavorites_GetClinicPrescriptionFavoritesV2
     */
    public function search(
        int $clinicId,
        \BackedEnum|string|null $favoriteType = null,
        ?int $pageNumber = null,
        ?int $pageSize = null,
        ?string $searchName = null,
    ): array {
        return $this->get("api/clinics/{$clinicId}/prescriptionFavorites", [
            'favoriteType' => $favoriteType,
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize,
            'searchName' => $searchName,
        ]);
    }

    /**
     * POST /api/clinics/{clinicId}/prescriptionFavorites/coded
     *
     * Add Clinic Coded Medication Favorite Prescription.
     *
     * @dosespot ClinicFavorites_AddClinicCodedMedicationFavoriteV2
     */
    public function createCoded(int $clinicId, array $favorite): array
    {
        return $this->post("api/clinics/{$clinicId}/prescriptionFavorites/coded", $favorite);
    }

    /**
     * PUT /api/clinics/{clinicId}/prescriptionFavorites/coded/{favoriteId}
     *
     * Edit Clinic Coded Medication Favorite Prescription.
     *
     * @dosespot ClinicFavorites_EditClinicCodedMedicationFavoriteV2
     */
    public function updateCoded(int $clinicId, int $favoriteId, array $favorite): array
    {
        return $this->put("api/clinics/{$clinicId}/prescriptionFavorites/coded/{$favoriteId}", $favorite);
    }

    /**
     * POST /api/clinics/{clinicId}/prescriptionFavorites/supply
     *
     * Add Clinic Supply Favorite Prescription.
     *
     * @dosespot ClinicFavorites_AddClinicSupplyFavoriteV2
     */
    public function createSupply(int $clinicId, array $favorite): array
    {
        return $this->post("api/clinics/{$clinicId}/prescriptionFavorites/supply", $favorite);
    }

    /**
     * PUT /api/clinics/{clinicId}/prescriptionFavorites/supply/{favoriteId}
     *
     * Edit Clinic Supply Favorite Prescription.
     *
     * @dosespot ClinicFavorites_EditClinicSupplyFavoriteV2
     */
    public function updateSupply(int $clinicId, int $favoriteId, array $favorite): array
    {
        return $this->put("api/clinics/{$clinicId}/prescriptionFavorites/supply/{$favoriteId}", $favorite);
    }

    /**
     * POST /api/clinics/{clinicId}/prescriptionFavorites/compiledcompound
     *
     * Add Clinic Compiled Compound Favorite Prescription.
     *
     * @dosespot ClinicFavorites_AddClinicCompiledCompoundFavoriteV2
     */
    public function createCompiledCompound(int $clinicId, array $favorite): array
    {
        return $this->post("api/clinics/{$clinicId}/prescriptionFavorites/compiledcompound", $favorite);
    }

    /**
     * PUT /api/clinics/{clinicId}/prescriptionFavorites/compiledcompound/{favoriteId}
     *
     * Edit Clinic Favorite Compiled Compound Prescription.
     *
     * @dosespot ClinicFavorites_EditClinicCompiledCompoundFavoriteV2
     */
    public function updateCompiledCompound(int $clinicId, int $favoriteId, array $favorite): array
    {
        return $this->put("api/clinics/{$clinicId}/prescriptionFavorites/compiledcompound/{$favoriteId}", $favorite);
    }
}
