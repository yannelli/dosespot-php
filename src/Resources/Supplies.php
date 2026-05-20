<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Supplies extends Resource
{
    /**
     * GET /api/supplies/search
     *
     * Search the medical supplies catalog by name and/or NDC code.
     */
    public function search(?string $name = null, ?string $ndc = null): array
    {
        return $this->get('api/supplies/search', [
            'name' => $name,
            'NDC' => $ndc,
        ]);
    }
}
