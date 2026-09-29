<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Supplies extends Resource
{
    /**
     * GET /api/supplies/{supplyId}
     *
     * Get supply information.
     *
     * @dosespot Supplies_GetSupplyByIDV2
     */
    public function find(int $supplyId): array
    {
        return $this->get("api/supplies/{$supplyId}");
    }

    /**
     * GET /api/supplies/search
     *
     * Search supplies matching the exact NDC xor exact UPC.
     * supplyStatus is Active, Inactive, or All.
     *
     * @dosespot Supplies_SuppliesSearchV2
     */
    public function search(
        ?string $name = null,
        ?string $ndc = null,
        ?string $upc = null,
        \BackedEnum|string|null $supplyStatus = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get('api/supplies/search', [
            'name' => $name,
            'ndc' => $ndc,
            'upc' => $upc,
            'supplyStatus' => $supplyStatus,
            'pageNumber' => $pageNumber,
        ]);
    }
}
