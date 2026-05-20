<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Medications extends Resource
{
    /**
     * GET /api/medications/search
     *
     * Search medications by name with full details.
     */
    public function search(string $name): array
    {
        return $this->get('api/medications/search', ['name' => $name]);
    }

    /**
     * GET /api/medications/basicSearch
     *
     * Lightweight medication search by name.
     */
    public function basicSearch(string $name): array
    {
        return $this->get('api/medications/basicSearch', ['name' => $name]);
    }

    /**
     * GET /api/medications/select
     *
     * Look up medication details by RxCUI, name, and strength.
     */
    public function select(string $rxCui, string $name, ?string $strength = null): array
    {
        return $this->get('api/medications/select', [
            'RxCUI' => $rxCui,
            'Name' => $name,
            'Strength' => $strength,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/medications/history
     *
     * Retrieve a patient's medication history.
     */
    public function history(
        int $patientId,
        \DateTimeInterface|string|null $start = null,
        \DateTimeInterface|string|null $end = null,
        ?int $onBehalfOfUserId = null,
    ): array {
        return $this->get("api/patients/{$patientId}/medications/history", [
            'start' => $start,
            'end' => $end,
            'onBehalfOfUserId' => $onBehalfOfUserId,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/medications/interactions
     *
     * Check drug-drug interactions for a patient.
     */
    public function interactions(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/medications/interactions");
    }
}
