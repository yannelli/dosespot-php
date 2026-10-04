<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Prescriptions extends Resource
{
    /**
     * GET /api/patients/{patientId}/prescriptions/{prescriptionId}
     *
     * Get Prescriptions.
     *
     * @dosespot Prescriptions_GetPatientPrescriptionV2
     */
    public function find(int $patientId, int $prescriptionId): array
    {
        return $this->get("api/patients/{$patientId}/prescriptions/{$prescriptionId}");
    }

    /**
     * GET /api/patients/{patientId}/prescriptions/{prescriptionId}/log
     *
     * Get Prescription Logs.
     *
     * @dosespot Prescriptions_GetPrescriptionLogDetailsV2
     */
    public function log(int $patientId, int $prescriptionId): array
    {
        return $this->get("api/patients/{$patientId}/prescriptions/{$prescriptionId}/log");
    }

    /**
     * GET /api/patients/{patientId}/prescriptions/{prescriptionId}/snapshot
     *
     * Get Precription snapshot.
     *
     * @dosespot Prescriptions_GetPrescriptionSnapshotV2
     */
    public function snapshot(int $patientId, int $prescriptionId): array
    {
        return $this->get("api/patients/{$patientId}/prescriptions/{$prescriptionId}/snapshot");
    }

    /**
     * GET /api/patients/{patientId}/prescriptions/{prescriptionId}/epcsSuggestions
     *
     * Get EPCS Suggested Prescription Schedule.
     *
     * @dosespot Prescriptions_GetEPCSSuggestionsV2
     */
    public function epcsSuggestions(int $patientId, int $prescriptionId): array
    {
        return $this->get("api/patients/{$patientId}/prescriptions/{$prescriptionId}/epcsSuggestions");
    }

    /**
     * GET /api/patients/{patientId}/prescriptions
     *
     * Get patient's prescriptions.
     * statusClass is Active, Inactive, or Pending.
     * sortColumn is DateWritten. sortOrder is Asc or Desc.
     *
     * @dosespot Prescriptions_GetPatientPrescriptionsV2
     */
    public function forPatient(
        int $patientId,
        \DateTimeInterface|string|null $startDate = null,
        \DateTimeInterface|string|null $endDate = null,
        \BackedEnum|string|null $statusClass = null,
        \BackedEnum|string|null $prescriptionStatus = null,
        ?int $pageNumber = null,
        \BackedEnum|string|null $sortColumn = null,
        \BackedEnum|string|null $sortOrder = null,
    ): array {
        return $this->get("api/patients/{$patientId}/prescriptions", [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'statusClass' => $statusClass,
            'prescriptionStatus' => $prescriptionStatus,
            'pageNumber' => $pageNumber,
            'sortColumn' => $sortColumn,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * DELETE /api/patients/{patientId}/prescriptions
     *
     * Delete Prescriptions.
     *
     * @dosespot Prescriptions_DeletePrescriptionsV2
     */
    public function deleteMany(int $patientId, array $payload): array
    {
        return $this->delete("api/patients/{$patientId}/prescriptions", body: $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/coded
     *
     * Add Coded Prescription.
     *
     * @dosespot Prescriptions_AddCodedPrescriptionV2
     */
    public function createCoded(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/coded", $prescription);
    }

    /**
     * PUT /api/patients/{patientId}/prescriptions/coded/{prescriptionId}
     *
     * Edit Coded Prescription.
     *
     * @dosespot Prescriptions_EditCodedPrescriptionV2
     */
    public function updateCoded(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->put("api/patients/{$patientId}/prescriptions/coded/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/supply
     *
     * Add Coded Supply Prescription.
     *
     * @dosespot Prescriptions_AddCodedSupplyV2
     */
    public function createSupply(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/supply", $prescription);
    }

    /**
     * PUT /api/patients/{patientId}/prescriptions/supply/{prescriptionId}
     *
     * Edit Coded Supply Prescription.
     *
     * @dosespot Prescriptions_EditCodedSupplyV2
     */
    public function updateSupply(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->put("api/patients/{$patientId}/prescriptions/supply/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/supply/freetext
     *
     * Add Free Text Supply Prescription.
     *
     * @dosespot Prescriptions_AddFreeTextSupplyPrescriptionV2
     */
    public function createFreetextSupply(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/supply/freetext", $prescription);
    }

    /**
     * PUT /api/patients/{patientId}/prescriptions/supply/freetext/{prescriptionId}
     *
     * Edit Free Text supply Prescription.
     *
     * @dosespot Prescriptions_EditFreeTextSupplyPrescriptionV2
     */
    public function updateFreetextSupply(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->put("api/patients/{$patientId}/prescriptions/supply/freetext/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/compiledcompound
     *
     * Add Compiled Compound.
     *
     * @dosespot Prescriptions_AddCompiledCompoundV2
     */
    public function createCompiledCompound(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/compiledcompound", $prescription);
    }

    /**
     * PUT /api/patients/{patientId}/prescriptions/compiledcompound/{prescriptionId}
     *
     * Edit Compiled Compound.
     *
     * @dosespot Prescriptions_EditCompiledCompoundV2
     */
    public function updateCompiledCompound(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->put("api/patients/{$patientId}/prescriptions/compiledcompound/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/send
     *
     * Send Prescriptions.
     *
     * @dosespot Prescriptions_SendPrescriptionsV2
     */
    public function send(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/send", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/sendEpcs
     *
     * Send EPCS Prescriptions.
     *
     * @dosespot Prescriptions_SendEpcsPrescriptionsV2
     */
    public function sendEpcs(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/sendEpcs", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/setPrinted
     *
     * Set Prescriptions Printed.
     *
     * @dosespot Prescriptions_SetPrescriptionsPrintedV2
     */
    public function setPrinted(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/setPrinted", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/readyToSign
     *
     * Set Prescriptions Ready To Sign.
     *
     * @dosespot Prescriptions_SetPrescriptionsReadyToSignV2
     */
    public function readyToSign(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/readyToSign", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/sendToAddress
     *
     * Send Prescriptions With Address.
     *
     * @dosespot Prescriptions_SendToAddressPrescriptionsV2
     */
    public function sendToAddress(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/sendToAddress", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/copy
     *
     * Copy Prescription For Patient.
     *
     * @dosespot Prescriptions_CopyPatientPrescriptionV2
     */
    public function copy(int $patientId, int $prescriptionId, array $payload = []): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/{$prescriptionId}/copy", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/cancel
     *
     * Cancel Prescription.
     *
     * @dosespot Prescriptions_CancelPrescriptionV2
     */
    public function cancel(int $patientId, int $prescriptionId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/{$prescriptionId}/cancel", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/ignoreError
     *
     * Ignore Error For Prescription.
     *
     * @dosespot Prescriptions_IgnoreAlertV2
     */
    public function ignoreError(int $patientId, int $prescriptionId, array $payload = []): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/{$prescriptionId}/ignoreError", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/medicationStatus
     *
     * Update Prescription's medication Status.
     *
     * @dosespot Prescriptions_UpdatePrescriptionStatusV2
     */
    public function updateMedicationStatus(int $patientId, int $prescriptionId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/{$prescriptionId}/medicationStatus", $payload);
    }

    /**
     * PATCH /api/patients/{patientId}/prescriptions/{prescriptionId}/pharmacy
     *
     * Change Prescription Pharmacy.
     *
     * @dosespot Prescriptions_ChangePrescriptionPharmacyV2
     */
    public function changePharmacy(int $patientId, int $prescriptionId, int $pharmacyId): array
    {
        return $this->patch("api/patients/{$patientId}/prescriptions/{$prescriptionId}/pharmacy", [
            'PharmacyId' => $pharmacyId,
        ]);
    }
}
