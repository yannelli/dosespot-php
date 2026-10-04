<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class General extends Resource
{
    /**
     * GET /api/general/check
     *
     * Check API health.
     *
     * @dosespot HealthCheck_CheckHealthV2
     */
    public function check(): array
    {
        return $this->get('api/general/check');
    }
}
