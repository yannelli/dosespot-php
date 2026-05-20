<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Diagnosis extends Resource
{
    /**
     * GET /api/diagnosis/searchByICD
     *
     * Search ICD-10 diagnosis codes by string.
     */
    public function searchByIcd(string $searchString): array
    {
        return $this->get('api/diagnosis/searchByICD', [
            'searchString' => $searchString,
        ]);
    }

    /**
     * GET /api/diagnosis/searchByCDT
     *
     * Search CDT dental procedure codes by string.
     */
    public function searchByCdt(string $searchString): array
    {
        return $this->get('api/diagnosis/searchByCDT', [
            'searchString' => $searchString,
        ]);
    }
}
