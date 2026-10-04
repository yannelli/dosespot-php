<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class DispenseUnits extends Resource
{
    /**
     * GET /api/dispenseUnits
     *
     * Get Dispense Units.
     *
     * @dosespot Medications_GetStandardDispenseUnitsV2
     */
    public function all(): array
    {
        return $this->get('api/dispenseUnits');
    }

    /**
     * GET /api/dispenseUnits/{dispenseUnitId}
     *
     * Get Dispense Unit By Id.
     *
     * @dosespot Medications_GetStandardDispenseUnitByIDV2
     */
    public function find(int $dispenseUnitId): array
    {
        return $this->get("api/dispenseUnits/{$dispenseUnitId}");
    }
}
