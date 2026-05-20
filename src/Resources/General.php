<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class General extends Resource
{
    /**
     * GET /api/general/check
     * Verifies that the API is reachable and the supplied credentials are valid.
     */
    public function check(): array
    {
        return $this->get('api/general/check');
    }
}
