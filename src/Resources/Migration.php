<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Migration extends Resource
{
    /**
     * POST /api/client/{clientId}/initiateDrugDbMigration
     *
     * Initiate the drug database migration for a specific client.
     */
    public function initiateDrugDb(int $clientId): array
    {
        return $this->post("api/client/{$clientId}/initiateDrugDbMigration");
    }
}
