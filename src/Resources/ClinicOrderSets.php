<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class ClinicOrderSets extends Resource
{
    /**
     * GET /api/clinic/ordersets
     *
     * Get clinic Order Sets. sortOrder is Asc or Desc.
     *
     * @dosespot ClinicOrderSets_GetClinicOrderSets
     */
    public function search(
        ?int $pageNumber = null,
        ?int $pageSize = null,
        \BackedEnum|string|null $sortOrder = null,
        ?string $searchString = null,
    ): array {
        return $this->get('api/clinic/ordersets', [
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize,
            'sortOrder' => $sortOrder,
            'searchString' => $searchString,
        ]);
    }
}
