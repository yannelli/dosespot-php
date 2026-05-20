<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Clinicians extends Resource
{
    /**
     * GET /api/clinicians/{clinicianId}
     */
    public function find(int $clinicianId): array
    {
        return $this->get("api/clinicians/{$clinicianId}");
    }

    /**
     * GET /api/clinician
     *
     * Look up a clinician by NPI and/or DEA number.
     */
    public function lookup(?string $npi = null, ?string $dea = null): array
    {
        return $this->get('api/clinician', ['npi' => $npi, 'dea' => $dea]);
    }

    /**
     * POST /api/clinicians
     */
    public function create(array $clinician): array
    {
        return $this->post('api/clinicians', $clinician);
    }

    /**
     * POST /api/clinicians/{clinicianId}
     *
     * Patch a clinician's record.
     */
    public function update(int $clinicianId, array $clinician): array
    {
        return $this->post("api/clinicians/{$clinicianId}", $clinician);
    }

    /**
     * PUT /api/clinicians/{clinicianId}
     *
     * Replace a clinician's record.
     */
    public function replace(int $clinicianId, array $clinician): array
    {
        return $this->put("api/clinicians/{$clinicianId}", $clinician);
    }

    /**
     * POST /api/clinicians/{clinicianId}/clinics
     *
     * @param  list<int>  $clinicIds
     */
    public function addClinics(int $clinicianId, array $clinicIds): array
    {
        return $this->post("api/clinicians/{$clinicianId}/clinics", [
            'ClinicIds' => $clinicIds,
        ]);
    }

    /**
     * GET /api/clinicians/{clinicianId}/registrationStatus
     */
    public function registrationStatus(int $clinicianId): array
    {
        return $this->get("api/clinicians/{$clinicianId}/registrationStatus");
    }

    /**
     * GET /api/clinicians/{clinicianId}/registrationStatusDetailed
     */
    public function registrationStatusDetailed(int $clinicianId): array
    {
        return $this->get("api/clinicians/{$clinicianId}/registrationStatusDetailed");
    }

    /**
     * GET /api/clinicians/{clinicianId}/legalAgreements
     */
    public function legalAgreements(int $clinicianId): array
    {
        return $this->get("api/clinicians/{$clinicianId}/legalAgreements");
    }

    /**
     * POST /api/clinicians/acceptAgreement
     */
    public function acceptAgreement(array $payload): array
    {
        return $this->post('api/clinicians/acceptAgreement', $payload);
    }

    /**
     * GET /api/clinicians/pdmp
     */
    public function pdmp(): array
    {
        return $this->get('api/clinicians/pdmp');
    }

    /**
     * GET /api/clinicians/{clinicianId}/idpDisclaimer
     */
    public function idpDisclaimer(int $clinicianId): array
    {
        return $this->get("api/clinicians/{$clinicianId}/idpDisclaimer");
    }

    /**
     * POST /api/clinicians/idpDisclaimer
     */
    public function acceptIdpDisclaimer(array $payload): array
    {
        return $this->post('api/clinicians/idpDisclaimer', $payload);
    }

    /**
     * POST /api/clinicians/{clinicianId}/initIdp
     */
    public function initIdp(int $clinicianId, array $payload = []): array
    {
        return $this->post("api/clinicians/{$clinicianId}/initIdp", $payload);
    }

    /**
     * POST /api/clinicians/idp
     */
    public function idp(array $payload): array
    {
        return $this->post('api/clinicians/idp', $payload);
    }

    /**
     * POST /api/clinicians/idpAnswers
     */
    public function submitIdpAnswers(array $payload): array
    {
        return $this->post('api/clinicians/idpAnswers', $payload);
    }

    /**
     * POST /api/clinicians/idpOtp
     */
    public function submitIdpOtp(array $payload): array
    {
        return $this->post('api/clinicians/idpOtp', $payload);
    }

    /**
     * GET /api/clinicians/idpStatus
     */
    public function idpStatus(string $sessionId): array
    {
        return $this->get('api/clinicians/idpStatus', ['sessionId' => $sessionId]);
    }

    /**
     * POST /api/clinicians/requestDuoMobileActivation
     */
    public function requestDuoMobileActivation(array $payload = []): array
    {
        return $this->post('api/clinicians/requestDuoMobileActivation', $payload);
    }

    /**
     * POST /api/clinicians/tfaActivate
     */
    public function activateTfa(array $payload): array
    {
        return $this->post('api/clinicians/tfaActivate', $payload);
    }

    /**
     * POST /api/clinicians/tfaDeactivate
     */
    public function deactivateTfa(array $payload): array
    {
        return $this->post('api/clinicians/tfaDeactivate', $payload);
    }

    /**
     * POST /api/clinicians/{clinicianId}/initTfaActivate
     */
    public function initTfaActivate(int $clinicianId, array $payload = []): array
    {
        return $this->post("api/clinicians/{$clinicianId}/initTfaActivate", $payload);
    }

    /**
     * POST /api/clinicians/{clinicianId}/initTfaDeactivate
     */
    public function initTfaDeactivate(int $clinicianId, array $payload = []): array
    {
        return $this->post("api/clinicians/{$clinicianId}/initTfaDeactivate", $payload);
    }

    /**
     * POST /api/clinicians/resyncToken
     */
    public function resyncToken(array $payload): array
    {
        return $this->post('api/clinicians/resyncToken', $payload);
    }

    /**
     * POST /api/clinicians/setPin
     */
    public function setPin(array $payload): array
    {
        return $this->post('api/clinicians/setPin', $payload);
    }

    /**
     * POST /api/clinicians/changePin
     */
    public function changePin(array $payload): array
    {
        return $this->post('api/clinicians/changePin', $payload);
    }
}
