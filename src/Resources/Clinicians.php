<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Clinicians extends Resource
{
    /**
     * GET /api/clinicians/{clinicianId}
     *
     * Get clinician information.
     *
     * @dosespot Clinicians_GetClinicianDetailsByIdV2
     */
    public function find(int $clinicianId): array
    {
        return $this->get("api/clinicians/{$clinicianId}");
    }

    /**
     * GET /api/clinician
     *
     * Get Clinician By Npi Or Dea.
     *
     * @dosespot Clinicians_GetClinicianByNpiOrDeaV2
     */
    public function lookup(?string $npi = null, ?string $dea = null): array
    {
        return $this->get('api/clinician', [
            'npi' => $npi,
            'dea' => $dea,
        ]);
    }

    /**
     * POST /api/clinicians
     *
     * Add new clinician.
     *
     * @dosespot Clinicians_ClinicianAddV2
     */
    public function create(array $clinician): array
    {
        return $this->post('api/clinicians', $clinician);
    }

    /**
     * PUT /api/clinicians/{clinicianId}
     *
     * Edit clinician information.
     *
     * @dosespot Clinicians_PutClinicianEditV2
     */
    public function update(int $clinicianId, array $clinician): array
    {
        return $this->put("api/clinicians/{$clinicianId}", $clinician);
    }

    /**
     * GET /api/patients/{patientId}/clinicians
     *
     * Get patient's clinicians.
     *
     * @dosespot Clinicians_GetPatientCliniciansV2
     */
    public function forPatient(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/clinicians");
    }

    /**
     * GET /api/clinicians/{clinicianId}/legalAgreements
     *
     * Retrieves a list of clinician's legal agreements.
     *
     * @dosespot Clinicians_GetClinicianLegalAgreementsV2
     */
    public function legalAgreements(int $clinicianId): array
    {
        return $this->get("api/clinicians/{$clinicianId}/legalAgreements");
    }

    /**
     * POST /api/clinicians/acceptAgreement
     *
     * Accepts a legal agreement.
     *
     * @dosespot Clinicians_AcceptAgreementV2
     */
    public function acceptAgreement(array $payload): array
    {
        return $this->post('api/clinicians/acceptAgreement', $payload);
    }

    /**
     * GET /api/clinicians/{clinicianId}/registrationStatus
     *
     * Get clinician specific registration status.
     *
     * @dosespot Clinicians_ClinicianCheckRegistrationStatusV2
     */
    public function registrationStatus(int $clinicianId): array
    {
        return $this->get("api/clinicians/{$clinicianId}/registrationStatus");
    }

    /**
     * GET /api/prescriptions/{prescriptionId}/prescriberInfo
     *
     * Get a Prescription's prescriber Information.
     *
     * @dosespot Clinicians_GetPrescriptionPrescriberInfoAPIV2
     */
    public function prescriberInfo(int $prescriptionId): array
    {
        return $this->get("api/prescriptions/{$prescriptionId}/prescriberInfo");
    }

    /**
     * GET /api/clinicians/pdmp
     *
     * Get Prescription Drug Monitoring Program data.
     *
     * @dosespot Clinicians_GetClinicianPDMPV2
     */
    public function pdmp(): array
    {
        return $this->get('api/clinicians/pdmp');
    }

    /**
     * POST /api/clinicians/pin
     *
     * Sets a clinician's PIN.
     *
     * @dosespot Clinicians_SetPinV2
     */
    public function setPin(array $payload): array
    {
        return $this->post('api/clinicians/pin', $payload);
    }

    /**
     * PUT /api/clinicians/pin
     *
     * Change a clinician's PIN.
     *
     * @dosespot Clinicians_ChangePinV2
     */
    public function changePin(array $payload): array
    {
        return $this->put('api/clinicians/pin', $payload);
    }

    /**
     * POST /api/clinicians/resetPin
     *
     * Reset My Pin.
     *
     * @dosespot Clinicians_ResetMyPinV2
     */
    public function resetMyPin(): array
    {
        return $this->post('api/clinicians/resetPin');
    }

    /**
     * POST /api/clinicians/{clinicianId}/resetPin
     *
     * Reset Clinician's PIN.
     *
     * @dosespot Clinicians_ResetClinicianPinV2
     */
    public function resetPin(int $clinicianId): array
    {
        return $this->post("api/clinicians/{$clinicianId}/resetPin");
    }

    /**
     * PUT /api/clinicians/{clinicianId}/supervisors
     *
     * Add a supervisor to a clinician.
     *
     * @param  list<array<string, mixed>>  $supervisors
     *
     * @dosespot Clinicians_AddSupervisorToClinicianV2
     */
    public function addSupervisors(int $clinicianId, array $supervisors): array
    {
        return $this->put("api/clinicians/{$clinicianId}/supervisors", $supervisors);
    }

    /**
     * DELETE /api/clinicians/{clinicianId}/supervisors
     *
     * Remove a supervisor from a clinician.
     *
     * @dosespot Clinicians_DeleteSupervisorToClinicianV2
     */
    public function removeSupervisors(int $clinicianId, array $payload): array
    {
        return $this->delete("api/clinicians/{$clinicianId}/supervisors", body: $payload);
    }

    /**
     * GET /api/clinicians/idpDisclaimer
     *
     * Get identity proofing disclaimer.
     *
     * @dosespot Clinicians_GetIdentityProofingDisclaimerV2
     */
    public function idpDisclaimer(): array
    {
        return $this->get('api/clinicians/idpDisclaimer');
    }

    /**
     * POST /api/clinicians/idpDisclaimer
     *
     * Accept identity proofing disclaimer.
     *
     * @dosespot Clinicians_AcceptIdentityProofingDisclaimerV2
     */
    public function acceptIdpDisclaimer(array $payload): array
    {
        return $this->post('api/clinicians/idpDisclaimer', $payload);
    }

    /**
     * POST /api/clinicians/{clinicianId}/idpInit
     *
     * Initialize identity proofing.
     *
     * @dosespot Clinicians_InitializeIDPV2
     */
    public function initIdp(int $clinicianId): array
    {
        return $this->post("api/clinicians/{$clinicianId}/idpInit");
    }

    /**
     * POST /api/clinicians/idp
     *
     * Clinician identity proofing.
     *
     * @dosespot Clinicians_ClinicianIdentityProofingV2
     */
    public function idp(array $payload): array
    {
        return $this->post('api/clinicians/idp', $payload);
    }

    /**
     * POST /api/clinicians/idpAnswers
     *
     * Submit clinician identity proofing answers.
     *
     * @dosespot Clinicians_ClinicianIdentityProofingAnswersV2
     */
    public function submitIdpAnswers(array $payload): array
    {
        return $this->post('api/clinicians/idpAnswers', $payload);
    }

    /**
     * POST /api/clinicians/idpOtp
     *
     * Submit IDP one time code.
     *
     * @dosespot Clinicians_ClinicianIdentityProofingOneTimeCodeV2
     */
    public function submitIdpOtp(array $payload): array
    {
        return $this->post('api/clinicians/idpOtp', $payload);
    }

    /**
     * POST /api/clinicians/resyncToken
     *
     * Resync clinician's token.
     *
     * @dosespot Clinicians_ResyncTokenV2
     */
    public function resyncToken(array $payload): array
    {
        return $this->post('api/clinicians/resyncToken', $payload);
    }

    /**
     * POST /api/clinicians/{clinicianId}/initTfaActivate
     *
     * Initialize two factor authentication.
     *
     * @dosespot Clinicians_InitializeTfaActivationV2
     */
    public function initTfaActivate(int $clinicianId, array $payload): array
    {
        return $this->post("api/clinicians/{$clinicianId}/initTfaActivate", $payload);
    }

    /**
     * POST /api/clinicians/tfaActivate
     *
     * Two factor authentication activation.
     *
     * @dosespot Clinicians_TfaActivateV2
     */
    public function activateTfa(array $payload): array
    {
        return $this->post('api/clinicians/tfaActivate', $payload);
    }

    /**
     * POST /api/clinicians/sendMobileTfa
     *
     * Send mobile activation message.
     *
     * @dosespot Clinicians_SendMobileTfaV2
     */
    public function sendMobileTfa(array $payload): array
    {
        return $this->post('api/clinicians/sendMobileTfa', $payload);
    }

    /**
     * POST /api/clinicians/{clinicianId}/initTfaDeactivate
     *
     * Initialize two factor authentication deactivation.
     *
     * @dosespot Clinicians_InitializeTfaDeactivationV2
     */
    public function initTfaDeactivate(int $clinicianId): array
    {
        return $this->post("api/clinicians/{$clinicianId}/initTfaDeactivate");
    }

    /**
     * POST /api/clinicians/tfaDeactivate
     *
     * Two factor authentication de-activation.
     *
     * @dosespot Clinicians_TfaDeactivateV2
     */
    public function deactivateTfa(array $payload): array
    {
        return $this->post('api/clinicians/tfaDeactivate', $payload);
    }

    /**
     * GET /api/clinicians/{clinicianId}/deanumbers
     *
     * Get clinician's DEA numbers.
     *
     * @dosespot DEANumbers_GetDEANumbers
     */
    public function deaNumbers(int $clinicianId): array
    {
        return $this->get("api/clinicians/{$clinicianId}/deanumbers");
    }

    /**
     * POST /api/clinicians/{clinicianId}/deanumbers
     *
     * Add a DEA number to a clinician.
     *
     * @dosespot DEANumbers_AddDEANumber
     */
    public function addDeaNumber(int $clinicianId, array $payload): array
    {
        return $this->post("api/clinicians/{$clinicianId}/deanumbers", $payload);
    }

    /**
     * GET /api/clinicians/{clinicianId}/deanumbers/{deanumberId}
     *
     * Get a clinician's DEA number.
     *
     * @dosespot DEANumbers_GetDEANumber
     */
    public function deaNumber(int $clinicianId, string $deaNumberId): array
    {
        return $this->get("api/clinicians/{$clinicianId}/deanumbers/{$deaNumberId}");
    }

    /**
     * PUT /api/clinicians/{clinicianId}/deanumbers/{deanumberId}
     *
     * Edit a DEA number to a clinician.
     *
     * @dosespot DEANumbers_EditDEANumber
     */
    public function updateDeaNumber(int $clinicianId, string $deaNumberId, array $payload): array
    {
        return $this->put("api/clinicians/{$clinicianId}/deanumbers/{$deaNumberId}", $payload);
    }

    /**
     * DELETE /api/clinicians/{clinicianId}/deanumbers/{deanumberId}
     *
     * Delete a clinician's DEA number.
     *
     * @dosespot DEANumbers_DeleteDEANumber
     */
    public function deleteDeaNumber(int $clinicianId, string $deaNumberId): array
    {
        return $this->delete("api/clinicians/{$clinicianId}/deanumbers/{$deaNumberId}");
    }
}
