<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Compounds extends Resource
{
    /**
     * GET /api/compounds/search
     *
     * Search the compound medication database by name and/or NDC.
     */
    public function search(?string $name = null, ?string $ndc = null): array
    {
        return $this->get('api/compounds/search', [
            'name' => $name,
            'ndc' => $ndc,
        ]);
    }
}
