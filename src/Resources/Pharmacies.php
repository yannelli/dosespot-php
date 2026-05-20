<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Pharmacies extends Resource
{
    /**
     * GET /api/pharmacies/{pharmacyId}
     *
     * Retrieve a pharmacy by id.
     */
    public function find(int $pharmacyId): array
    {
        return $this->get("api/pharmacies/{$pharmacyId}");
    }

    /**
     * GET /api/pharmacies/search
     *
     * Search the pharmacy directory by any combination of filters.
     *
     * @param  list<int>|null  $specialty
     */
    public function search(
        ?string $name = null,
        ?string $city = null,
        ?string $state = null,
        ?string $zip = null,
        ?string $address = null,
        ?string $phoneOrFax = null,
        ?array $specialty = null,
        ?string $ncpdpID = null,
    ): array {
        $query = [
            'name' => $name,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'address' => $address,
            'phoneOrFax' => $phoneOrFax,
            'ncpdpID' => $ncpdpID,
        ];

        if ($specialty !== null) {
            foreach (array_values($specialty) as $i => $value) {
                $query["specialty[{$i}]"] = $value;
            }
        }

        return $this->get('api/pharmacies/search', $query);
    }

    /**
     * POST /api/patients/{patientId}/pharmacies/{pharmacyId}
     *
     * Add a pharmacy to a patient's preferred-pharmacy list.
     */
    public function addToPatient(int $patientId, int $pharmacyId): array
    {
        return $this->post("api/patients/{$patientId}/pharmacies/{$pharmacyId}");
    }

    /**
     * DELETE /api/patients/{patientId}/pharmacies/{pharmacyId}
     *
     * Remove a pharmacy from a patient's preferred-pharmacy list.
     */
    public function removeFromPatient(int $patientId, int $pharmacyId): array
    {
        return $this->delete("api/patients/{$patientId}/pharmacies/{$pharmacyId}");
    }
}
