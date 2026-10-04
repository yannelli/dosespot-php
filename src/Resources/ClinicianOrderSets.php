<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class ClinicianOrderSets extends Resource
{
    /**
     * GET /api/clinicians/ordersets
     *
     * Get clinician Order Sets. sortOrder is Asc or Desc.
     *
     * @dosespot ClinicianOrderSets_GetClinicianOrderSetsV2
     */
    public function search(
        ?int $pageNumber = null,
        ?int $pageSize = null,
        \BackedEnum|string|null $sortOrder = null,
        ?string $searchString = null,
    ): array {
        return $this->get('api/clinicians/ordersets', [
            'pageNumber' => $pageNumber,
            'pageSize' => $pageSize,
            'sortOrder' => $sortOrder,
            'searchString' => $searchString,
        ]);
    }

    /**
     * GET /api/clinicians/ordersets/{orderSetId}/details
     *
     * Get an order set details.
     *
     * @dosespot ClinicianOrderSets_GetClinicianOrderSetDetailsV2
     */
    public function details(int $orderSetId): array
    {
        return $this->get("api/clinicians/ordersets/{$orderSetId}/details");
    }

    /**
     * POST /api/clinicians/ordersets
     *
     * Add an Order Set.
     *
     * @dosespot ClinicianOrderSets_AddClinicianOrderSetV2
     */
    public function create(array $orderSet): array
    {
        return $this->post('api/clinicians/ordersets', $orderSet);
    }

    /**
     * PUT /api/clinicians/ordersets/{orderSetId}
     *
     * Update an Order Set.
     *
     * @dosespot ClinicianOrderSets_UpdateClinicianOrderSetV2
     */
    public function update(int $orderSetId, array $orderSet): array
    {
        return $this->put("api/clinicians/ordersets/{$orderSetId}", $orderSet);
    }

    /**
     * DELETE /api/clinicians/ordersets/{orderSetId}
     *
     * Delete an order set.
     *
     * @dosespot ClinicianOrderSets_DeleteClinicianOrderSetV2
     */
    public function deleteOrderSet(int $orderSetId): array
    {
        return $this->delete("api/clinicians/ordersets/{$orderSetId}");
    }
}
