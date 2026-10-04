<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class RxChange extends Resource
{
    /**
     * GET /api/rxchanges/pending
     *
     * Get Clinician's Rx Change requests for clinic(s)/patient.
     * clinic is All or Current.
     *
     * @dosespot RxChanges_GetRxChangeQueueV2
     */
    public function pending(
        \BackedEnum|string|null $clinic = null,
        ?int $patientId = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get('api/rxchanges/pending', [
            'clinic' => $clinic,
            'patientId' => $patientId,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * GET /api/rxchanges/pending/detailed
     *
     * Get Clinician's Rx Change requests for clinic(s)/patient.
     * clinic is All or Current.
     *
     * @dosespot RxChanges_GetRxChangeQueueDetailedV2
     */
    public function pendingDetailed(
        \BackedEnum|string|null $clinic = null,
        ?int $patientId = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get('api/rxchanges/pending/detailed', [
            'clinic' => $clinic,
            'patientId' => $patientId,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * POST /api/rxchanges/{rxChangeId}/approve
     *
     * Approves a pending rxChange request.
     *
     * @dosespot RxChanges_ApproveRxChangeV2
     */
    public function approve(int $rxChangeId, array $payload): array
    {
        return $this->post("api/rxchanges/{$rxChangeId}/approve", $payload);
    }

    /**
     * POST /api/rxchanges/{rxChangeId}/deny
     *
     * Denies a pending rxChange request.
     *
     * @dosespot RxChanges_DenyRxChangeV2
     */
    public function deny(int $rxChangeId, array $payload): array
    {
        return $this->post("api/rxchanges/{$rxChangeId}/deny", $payload);
    }

    /**
     * POST /api/rxchanges/{rxChangeId}/patients/{patientId}/reconcile
     *
     * Reconcile Rx. Change.
     *
     * @dosespot RxChanges_ReconcileRxChangeV2
     */
    public function reconcile(int $rxChangeId, int $patientId, int $referencedPrescriptionId): array
    {
        return $this->post("api/rxchanges/{$rxChangeId}/patients/{$patientId}/reconcile", [
            'ReferencedPrescriptionId' => $referencedPrescriptionId,
        ]);
    }

    /**
     * PATCH /api/rxchanges/{rxChangeId}/changePatient
     *
     * Change patient details in a rxchange request.
     *
     * @dosespot RxChanges_ChangeRxChangeRequestPatientV2
     */
    public function changePatient(int $rxChangeId, array $payload): array
    {
        return $this->patch("api/rxchanges/{$rxChangeId}/changePatient", $payload);
    }
}
