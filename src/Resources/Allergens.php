<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Allergens extends Resource
{
    /**
     * GET /api/allergens/{allergenId}
     *
     * Search allergen by ID.
     *
     * @dosespot Allergens_GetAllergenByIDV2
     */
    public function find(int $allergenId): array
    {
        return $this->get("api/allergens/{$allergenId}");
    }

    /**
     * GET /api/allergens/searchByRxCUI
     *
     * Search allergens by RxCUI.
     *
     * @dosespot Allergens_SearchByRxCUI
     */
    public function searchByRxCui(string $rxCui): array
    {
        return $this->get('api/allergens/searchByRxCUI', [
            'rxCui' => $rxCui,
        ]);
    }

    /**
     * GET /api/allergens/search
     *
     * Search allergens by name.
     *
     * @dosespot Allergens_AllergenSearchV2
     */
    public function search(string $name, ?int $pageNumber = null): array
    {
        return $this->get('api/allergens/search', [
            'name' => $name,
            'pageNumber' => $pageNumber,
        ]);
    }
}
