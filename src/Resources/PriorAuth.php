<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class PriorAuth extends Resource
{
    /**
     * POST /api/priorAuth/initiate
     */
    public function initiate(array $payload): array
    {
        return $this->post('api/priorAuth/initiate', $payload);
    }

    /**
     * GET /api/priorAuth/{priorAuthId}
     */
    public function find(int $priorAuthId): array
    {
        return $this->get("api/priorAuth/{$priorAuthId}");
    }

    /**
     * GET /api/priorAuth/{priorAuthId}/history
     */
    public function history(int $priorAuthId): array
    {
        return $this->get("api/priorAuth/{$priorAuthId}/history");
    }

    /**
     * GET /api/priorAuth/patients/{patientId}
     */
    public function forPatient(int $patientId): array
    {
        return $this->get("api/priorAuth/patients/{$patientId}");
    }

    /**
     * GET /api/priorAuth/{priorAuthId}/questions/{questionId}
     */
    public function question(int $priorAuthId, int $questionId): array
    {
        return $this->get("api/priorAuth/{$priorAuthId}/questions/{$questionId}");
    }

    /**
     * POST /api/priorAuth/{priorAuthId}/answer/{questionId}
     */
    public function answer(int $priorAuthId, int $questionId, array $payload): array
    {
        return $this->post("api/priorAuth/{$priorAuthId}/answer/{$questionId}", $payload);
    }

    /**
     * POST /api/priorAuth/{priorAuthId}/submit
     */
    public function submit(int $priorAuthId, array $payload = []): array
    {
        return $this->post("api/priorAuth/{$priorAuthId}/submit", $payload);
    }

    /**
     * POST /api/priorAuth/{priorAuthId}/attach
     */
    public function attach(int $priorAuthId, array $payload): array
    {
        return $this->post("api/priorAuth/{$priorAuthId}/attach", $payload);
    }

    /**
     * GET /api/priorAuth/{priorAuthId}/attachment/{attachmentId}
     */
    public function attachment(int $priorAuthId, int $attachmentId): array
    {
        return $this->get("api/priorAuth/{$priorAuthId}/attachment/{$attachmentId}");
    }

    /**
     * DELETE /api/priorAuth/{priorAuthId}/attachment/{attachmentId}/delete
     */
    public function deleteAttachment(int $priorAuthId, int $attachmentId): array
    {
        return $this->delete("api/priorAuth/{$priorAuthId}/attachment/{$attachmentId}/delete");
    }

    /**
     * POST /api/priorAuth/{priorAuthId}/appeal
     */
    public function appeal(int $priorAuthId, array $payload): array
    {
        return $this->post("api/priorAuth/{$priorAuthId}/appeal", $payload);
    }

    /**
     * POST /api/priorAuth/{priorAuthId}/approveOffline
     */
    public function approveOffline(int $priorAuthId, array $payload = []): array
    {
        return $this->post("api/priorAuth/{$priorAuthId}/approveOffline", $payload);
    }

    /**
     * POST /api/priorAuth/{priorAuthId}/denyOffline
     */
    public function denyOffline(int $priorAuthId, array $payload = []): array
    {
        return $this->post("api/priorAuth/{$priorAuthId}/denyOffline", $payload);
    }

    /**
     * POST /api/priorAuth/{priorAuthId}/remove
     */
    public function remove(int $priorAuthId, array $payload = []): array
    {
        return $this->post("api/priorAuth/{$priorAuthId}/remove", $payload);
    }

    /**
     * POST /api/priorAuth/{priorAuthId}/cancel
     */
    public function cancel(int $priorAuthId, array $payload = []): array
    {
        return $this->post("api/priorAuth/{$priorAuthId}/cancel", $payload);
    }
}
