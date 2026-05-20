<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Patients extends Resource
{
    /**
     * GET /api/patients/search
     *
     * Search patients by name and/or date of birth.
     */
    public function search(
        ?string $firstName = null,
        ?string $lastName = null,
        \DateTimeInterface|string|null $dob = null,
        ?string $status = null,
        ?int $pageNumber = null,
    ): array {
        return $this->get('api/patients/search', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'dob' => $dob,
            'status' => $status,
            'pageNumber' => $pageNumber,
        ]);
    }

    /**
     * GET /api/patients/{patientId}
     */
    public function find(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}");
    }

    /**
     * GET /api/patients/{patientId}/details
     */
    public function details(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/details");
    }

    /**
     * POST /api/patients
     */
    public function create(array $patient): array
    {
        return $this->post('api/patients', $patient);
    }

    /**
     * POST /api/patients/{patientId}
     */
    public function update(int $patientId, array $patient): array
    {
        return $this->post("api/patients/{$patientId}", $patient);
    }

    /**
     * GET /api/patients/{patientId}/pharmacies
     */
    public function pharmacies(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/pharmacies");
    }

    /**
     * GET /api/patients/{patientId}/prescriptions
     */
    public function prescriptions(
        int $patientId,
        \DateTimeInterface|string|null $startDate = null,
        \DateTimeInterface|string|null $endDate = null,
    ): array {
        return $this->get("api/patients/{$patientId}/prescriptions", [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/selfReportedMedications
     */
    public function selfReportedMedications(
        int $patientId,
        \DateTimeInterface|string|null $startDate = null,
        \DateTimeInterface|string|null $endDate = null,
    ): array {
        return $this->get("api/patients/{$patientId}/selfReportedMedications", [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * GET /api/patients/{patientId}/clinics
     */
    public function clinics(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/clinics");
    }

    /**
     * GET /api/patients/{patientId}/clinicians
     */
    public function clinicians(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/clinicians");
    }

    /**
     * POST /api/patients/merge
     */
    public function merge(array $payload): array
    {
        return $this->post('api/patients/merge', $payload);
    }

    /**
     * POST /api/patients/{patientId}/logMedicationHistoryConsent
     */
    public function logMedicationHistoryConsent(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/logMedicationHistoryConsent", $payload);
    }

    /**
     * POST /api/patients/{patientId}/precheckInteractions
     */
    public function precheckInteractions(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/precheckInteractions", $payload);
    }

    /**
     * POST /api/patients/{patientId}/transfer
     */
    public function transfer(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/transfer", $payload);
    }

    /**
     * POST /api/patients/{patientId}/insurance
     */
    public function setInsurance(int $patientId, array $payload): array
    {
        return $this->post("api/patients/{$patientId}/insurance", $payload);
    }
}
