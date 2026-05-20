<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Prescriptions extends Resource
{
    /**
     * GET /api/patients/{patientId}/prescriptions/{prescriptionId}
     */
    public function find(int $patientId, int $prescriptionId): array
    {
        return $this->get("api/patients/{$patientId}/prescriptions/{$prescriptionId}");
    }

    /**
     * GET /api/patients/{patientId}/prescriptions/{prescriptionId}/epcsSuggestions
     */
    public function epcsSuggestions(int $patientId, int $prescriptionId): array
    {
        return $this->get("api/patients/{$patientId}/prescriptions/{$prescriptionId}/epcsSuggestions");
    }

    /**
     * GET /api/patients/{patientId}/prescriptions/{prescriptionId}/log
     */
    public function log(int $patientId, int $prescriptionId): array
    {
        return $this->get("api/patients/{$patientId}/prescriptions/{$prescriptionId}/log");
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/coded
     */
    public function createCoded(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/coded", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/coded/{prescriptionId}
     */
    public function updateCoded(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/coded/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/ndc
     */
    public function createNdc(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/ndc", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/ndc/{prescriptionId}
     */
    public function updateNdc(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/ndc/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/freetext
     */
    public function createFreetext(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/freetext", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/freetext/{prescriptionId}
     */
    public function updateFreetext(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/freetext/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/compound
     */
    public function createCompound(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/compound", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/compound/{prescriptionId}
     */
    public function updateCompound(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/compound/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/compiledcompound
     */
    public function createCompiledCompound(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/compiledcompound", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/compiledcompound/{prescriptionId}
     */
    public function updateCompiledCompound(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/compiledcompound/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/supply
     */
    public function createSupply(int $patientId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/supply", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/supply/{prescriptionId}
     */
    public function updateSupply(int $patientId, int $prescriptionId, array $prescription): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/supply/{$prescriptionId}", $prescription);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/copy
     */
    public function copy(int $patientId, int $prescriptionId): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/{$prescriptionId}/copy");
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/changePharmacy
     */
    public function changePharmacy(int $patientId, int $prescriptionId, int $pharmacyId): array
    {
        return $this->post(
            "api/patients/{$patientId}/prescriptions/{$prescriptionId}/changePharmacy",
            null,
            ['pharmacyId' => $pharmacyId],
        );
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/ignoreError
     */
    public function ignoreError(int $patientId, int $prescriptionId): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/{$prescriptionId}/ignoreError");
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/updateStatus
     */
    public function updateStatus(int $patientId, int $prescriptionId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/{$prescriptionId}/updateStatus", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/setPrinted
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/setPrinted/{pin}
     */
    public function setPrinted(int $patientId, int $prescriptionId, ?string $pin = null): array
    {
        $path = "api/patients/{$patientId}/prescriptions/{$prescriptionId}/setPrinted";

        if ($pin !== null) {
            $path .= "/{$pin}";
        }

        return $this->post($path);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/setPrinted
     * POST /api/patients/{patientId}/prescriptions/setPrinted/{pin}
     *
     * @param  list<int>  $prescriptionIds
     */
    public function setPrintedBulk(int $patientId, array $prescriptionIds, ?string $pin = null): array
    {
        $path = "api/patients/{$patientId}/prescriptions/setPrinted";

        if ($pin !== null) {
            $path .= "/{$pin}";
        }

        return $this->post($path, ['PrescriptionIds' => $prescriptionIds]);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/send
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/send/{pin}
     */
    public function send(int $patientId, int $prescriptionId, ?string $pin = null): array
    {
        $path = "api/patients/{$patientId}/prescriptions/{$prescriptionId}/send";

        if ($pin !== null) {
            $path .= "/{$pin}";
        }

        return $this->post($path);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/send
     * POST /api/patients/{patientId}/prescriptions/send/{pin}
     *
     * @param  list<int>  $prescriptionIds
     */
    public function sendBulk(int $patientId, array $prescriptionIds, ?string $pin = null): array
    {
        $path = "api/patients/{$patientId}/prescriptions/send";

        if ($pin !== null) {
            $path .= "/{$pin}";
        }

        return $this->post($path, ['PrescriptionIds' => $prescriptionIds]);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/sendOnBehalfOf/{onBehalfOf}
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/sendOnBehalfOf/{onBehalfOf}/{pin}
     */
    public function sendOnBehalfOf(int $patientId, int $prescriptionId, int $onBehalfOf, ?string $pin = null): array
    {
        $path = "api/patients/{$patientId}/prescriptions/{$prescriptionId}/sendOnBehalfOf/{$onBehalfOf}";

        if ($pin !== null) {
            $path .= "/{$pin}";
        }

        return $this->post($path);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/sendOnBehalfOf/{onBehalfOf}
     * POST /api/patients/{patientId}/prescriptions/sendOnBehalfOf/{onBehalfOf}/{pin}
     *
     * @param  list<int>  $prescriptionIds
     */
    public function sendBulkOnBehalfOf(int $patientId, int $onBehalfOf, array $prescriptionIds, ?string $pin = null): array
    {
        $path = "api/patients/{$patientId}/prescriptions/sendOnBehalfOf/{$onBehalfOf}";

        if ($pin !== null) {
            $path .= "/{$pin}";
        }

        return $this->post($path, ['PrescriptionIds' => $prescriptionIds]);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/sendEpcs
     */
    public function sendEpcs(int $patientId, int $prescriptionId, array $payload = []): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/{$prescriptionId}/sendEpcs", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/sendEpcs
     */
    public function sendEpcsBulk(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/sendEpcs", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/cancel
     */
    public function cancel(int $patientId, int $prescriptionId, array $payload = []): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/{$prescriptionId}/cancel", $payload);
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/{prescriptionId}/cancelOnBehalfOf/{onBehalfOf}
     */
    public function cancelOnBehalfOf(int $patientId, int $prescriptionId, int $onBehalfOf, array $payload = []): array
    {
        return $this->post(
            "api/patients/{$patientId}/prescriptions/{$prescriptionId}/cancelOnBehalfOf/{$onBehalfOf}",
            $payload,
        );
    }

    /**
     * DELETE /api/patients/{patientId}/prescriptions/{prescriptionId}/delete
     */
    public function destroy(int $patientId, int $prescriptionId): array
    {
        return $this->delete("api/patients/{$patientId}/prescriptions/{$prescriptionId}/delete");
    }

    /**
     * DELETE /api/patients/{patientId}/prescriptions/delete
     *
     * @param  list<int>  $prescriptionIds
     */
    public function destroyBulk(int $patientId, array $prescriptionIds): array
    {
        return $this->raw('DELETE', "api/patients/{$patientId}/prescriptions/delete", [
            'PrescriptionIds' => $prescriptionIds,
        ])->json();
    }

    /**
     * POST /api/patients/{patientId}/prescriptions/readyToSign
     *
     * @param  list<int>  $prescriptionIds
     */
    public function readyToSign(int $patientId, array $prescriptionIds): array
    {
        return $this->post("api/patients/{$patientId}/prescriptions/readyToSign", [
            'PrescriptionIds' => $prescriptionIds,
        ]);
    }
}
