<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class DispenseUnits extends Resource
{
    /**
     * GET /api/dispenseUnits
     *
     * List all available dispense units used for prescription quantities.
     */
    public function all(): array
    {
        return $this->get('api/dispenseUnits');
    }

    /**
     * GET /api/units/dispenseUnits/{id}
     *
     * Retrieve a specific dispense unit by id.
     */
    public function find(int $id): array
    {
        return $this->get("api/units/dispenseUnits/{$id}");
    }
}
