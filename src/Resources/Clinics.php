<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Clinics extends Resource
{
    /**
     * GET /api/clinics/{clinicId}
     */
    public function find(int $clinicId): array
    {
        return $this->get("api/clinics/{$clinicId}");
    }

    /**
     * POST /api/clinics
     */
    public function create(array $clinic): array
    {
        return $this->post('api/clinics', $clinic);
    }

    /**
     * POST /api/clinics/{clinicId}
     *
     * Update specific fields on a clinic.
     */
    public function update(int $clinicId, array $clinic): array
    {
        return $this->post("api/clinics/{$clinicId}", $clinic);
    }

    /**
     * PUT /api/clinics/{clinicId}
     *
     * Replace a clinic's data.
     */
    public function replace(int $clinicId, array $clinic): array
    {
        return $this->put("api/clinics/{$clinicId}", $clinic);
    }

    /**
     * POST /api/clinics/clinicRemoveClinicians
     *
     * Remove clinician associations from a clinic.
     */
    public function removeClinicians(int $clinicId, array $payload = []): array
    {
        return $this->post('api/clinics/clinicRemoveClinicians', $payload, ['clinicId' => $clinicId]);
    }

    /**
     * POST /api/clinics/clinicGroup
     *
     * Create a clinic group.
     */
    public function createGroup(array $group): array
    {
        return $this->post('api/clinics/clinicGroup', $group);
    }
}
