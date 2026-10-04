<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Pharmacies extends Resource
{
    /**
     * GET /api/pharmacies/{pharmacyId}
     *
     * Get pharmacy details.
     *
     * @dosespot Pharmacies_GetPharmacyV2
     */
    public function find(int $pharmacyId): array
    {
        return $this->get("api/pharmacies/{$pharmacyId}");
    }

    /**
     * GET /api/pharmacies/search
     *
     * Search pharmacies. Specialty values are repeated query keys.
     *
     * @param  list<string|\BackedEnum>|null  $specialty
     *
     * @dosespot Pharmacies_PharmacySearchV2
     */
    public function search(
        ?string $name = null,
        ?string $city = null,
        ?string $state = null,
        ?string $zip = null,
        ?string $address = null,
        ?string $phoneOrFax = null,
        ?array $specialty = null,
        ?string $ncpdpId = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get('api/pharmacies/search', [
            'name' => $name,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'address' => $address,
            'phoneOrFax' => $phoneOrFax,
            'specialty' => $specialty,
            'ncpdpId' => $ncpdpId,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/pharmacies
     *
     * Get patient's pharmacies.
     *
     * @dosespot Pharmacies_GetPatientPharmaciesV2
     */
    public function forPatient(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/pharmacies");
    }

    /**
     * POST /api/patients/{patientId}/pharmacies
     *
     * Add Pharmacy to Patient.
     *
     * @dosespot Pharmacies_AddPatientPharmacyV2
     */
    public function addToPatient(int $patientId, int $pharmacyId, ?bool $setAsPrimary = null): array
    {
        $body = ['PharmacyId' => $pharmacyId];

        if ($setAsPrimary !== null) {
            $body['SetAsPrimary'] = $setAsPrimary;
        }

        return $this->post("api/patients/{$patientId}/pharmacies", $body);
    }

    /**
     * DELETE /api/patients/{patientId}/pharmacies/{pharmacyId}
     *
     * Delete Pharmacy from Patient.
     *
     * @dosespot Pharmacies_RemovePatientPharmacyV2
     */
    public function removeFromPatient(int $patientId, int $pharmacyId): array
    {
        return $this->delete("api/patients/{$patientId}/pharmacies/{$pharmacyId}");
    }

    /**
     * GET /api/pharmacies/restrictions
     *
     * Get Pharmacy Restrictions.
     *
     * @dosespot Pharmacies_GetPharmacyRestrictionsV2
     */
    public function restrictions(int $dispensableDrugId, ?int $pageNumber = null): array
    {
        return $this->get('api/pharmacies/restrictions', [
            'dispensableDrugId' => $dispensableDrugId,
            'pageNumber' => $pageNumber,
        ]);
    }
}
