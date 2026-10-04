<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class ClinicianFavorites extends Resource
{
    /**
     * GET /api/clinicians/{clinicianId}/prescriptionFavorites
     *
     * Get clinician's favorite prescriptions.
     * favoriteType is All, Medication, Supply, or CompiledCompound.
     *
     * @dosespot ClinicianFavorites_GetClinicianPrescriptionFavoritesV2
     */
    public function search(
        int $clinicianId,
        \BackedEnum|string|null $favoriteType = null,
        ?int $pageNumber = null,
        ?int $pageSize = null,
        ?string $searchName = null,
    ): array {
        return $this->get("api/clinicians/{$clinicianId}/prescriptionFavorites", [
            'favoriteType' => $favoriteType,
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize,
            'searchName' => $searchName,
        ]);
    }

    /**
     * POST /api/clinicians/prescriptionFavorites/coded
     *
     * Add clinician favorite coded Prescription.
     *
     * @dosespot ClinicianFavorites_AddClinicianPrescriptionFavoriteV2
     */
    public function createCoded(array $favorite): array
    {
        return $this->post('api/clinicians/prescriptionFavorites/coded', $favorite);
    }

    /**
     * PUT /api/clinicians/prescriptionFavorites/coded/{favoriteId}
     *
     * Edit Clinician's Coded Favorite Prescription.
     *
     * @dosespot ClinicianFavorites_EditClinicianCodedPrescriptionFavoriteV2
     */
    public function updateCoded(int $favoriteId, array $favorite): array
    {
        return $this->put("api/clinicians/prescriptionFavorites/coded/{$favoriteId}", $favorite);
    }

    /**
     * POST /api/clinicians/prescriptionFavorites/supply
     *
     * Add Clinician Supply Favorite Prescription.
     *
     * @dosespot ClinicianFavorites_AddClinicianSupplyFavoriteV2
     */
    public function createSupply(array $favorite): array
    {
        return $this->post('api/clinicians/prescriptionFavorites/supply', $favorite);
    }

    /**
     * PUT /api/clinicians/prescriptionFavorites/supply/{favoriteId}
     *
     * Edit Clinician's Supply Favorite Prescription.
     *
     * @dosespot ClinicianFavorites_EditClinicianSupplyFavoriteV2
     */
    public function updateSupply(int $favoriteId, array $favorite): array
    {
        return $this->put("api/clinicians/prescriptionFavorites/supply/{$favoriteId}", $favorite);
    }

    /**
     * POST /api/clinicians/prescriptionFavorites/compiledcompound
     *
     * Add Clinician Compiled Compound Favorite Prescription.
     *
     * @dosespot ClinicianFavorites_AddClinicianCompiledCompoundFavoriteV2
     */
    public function createCompiledCompound(array $favorite): array
    {
        return $this->post('api/clinicians/prescriptionFavorites/compiledcompound', $favorite);
    }

    /**
     * PUT /api/clinicians/prescriptionFavorites/compiledcompound/{favoriteId}
     *
     * Edit Clinician's Favorite Compiled Compound Prescription.
     *
     * @dosespot ClinicianFavorites_EditClinicianCompiledCompoundFavoriteV2
     */
    public function updateCompiledCompound(int $favoriteId, array $favorite): array
    {
        return $this->put("api/clinicians/prescriptionFavorites/compiledcompound/{$favoriteId}", $favorite);
    }

    /**
     * DELETE /api/clinicians/prescriptionFavorites
     *
     * Delete Clinician Favorites.
     *
     * @dosespot ClinicianFavorites_DeleteClinicianFavoriteV2
     */
    public function deleteFavorites(array $payload): array
    {
        return $this->delete('api/clinicians/prescriptionFavorites', body: $payload);
    }
}
