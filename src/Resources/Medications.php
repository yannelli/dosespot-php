<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Medications extends Resource
{
    /**
     * GET /api/medications/search
     *
     * Search for drugs. drugStatus is Active, Inactive, or All.
     *
     * @dosespot Medications_MedicationSearchV2
     */
    public function search(string $name, \BackedEnum|string|null $drugStatus = null, ?int $pageNumber = null): array
    {
        return $this->get('api/medications/search', [
            'name' => $name,
            'drugStatus' => $drugStatus,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * GET /api/medications/searchByRxCUI
     *
     * Get dispensable drugs by rxCUI.
     *
     * @dosespot Medications_MedicationSearchRxCUIV2
     */
    public function searchByRxCui(?string $rxCui = null): array
    {
        return $this->get('api/medications/searchByRxCUI', [
            'rxCUI' => $rxCui,
        ]);
    }

    /**
     * GET /api/medications/select
     *
     * Get dispensable drug detail.
     *
     * @dosespot Medications_MedicationSelectV2
     */
    public function select(?int $dispensableDrugId = null, ?string $ndc = null, ?int $rxcui = null): array
    {
        return $this->get('api/medications/select', [
            'dispensableDrugId' => $dispensableDrugId,
            'nDC' => $ndc,
            'rXCUI' => $rxcui,
        ]);
    }

    /**
     * GET /api/medications/monograph
     *
     * Get Drug Monograph. monographFormat is HTML or XML.
     *
     * @dosespot Medications_GetDrugMonographV2
     */
    public function monograph(
        ?int $dispensableDrugId = null,
        ?string $ndc = null,
        \BackedEnum|string|null $monographFormat = null,
    ): array {
        return $this->get('api/medications/monograph', [
            'dispensableDrugId' => $dispensableDrugId,
            'nDC' => $ndc,
            'monographFormat' => $monographFormat,
        ]);
    }
}
