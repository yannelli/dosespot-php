<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class PriorAuth extends Resource
{
    /**
     * GET /api/priorAuths/{priorAuthId}
     *
     * Get Single Prior Authorization Case By Id.
     *
     * @dosespot PriorAuthorizations_GetSinglePriorAuthorizationCaseByIdV2
     */
    public function find(int $priorAuthId): array
    {
        return $this->get("api/priorAuths/{$priorAuthId}");
    }

    /**
     * GET /api/patients/{patientId}/priorAuths
     *
     * List PA Cases In Progress For Patient.
     *
     * @dosespot PriorAuthorizations_GetInProgressPriorAuthCasesV2
     */
    public function forPatient(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/priorAuths");
    }

    /**
     * GET /api/priorAuths/{priorAuthId}/history
     *
     * Get PA history of a case.
     *
     * @dosespot PriorAuthorizations_GetPAHistoryV2
     */
    public function history(int $priorAuthId): array
    {
        return $this->get("api/priorAuths/{$priorAuthId}/history");
    }

    /**
     * GET /api/priorAuths/{priorAuthId}/statusLog
     *
     * Get Status Log for Specific Prior Authorization Case By Id.
     *
     * @dosespot PriorAuthorizations_GetPriorAuthorizationStatusLogV2
     */
    public function statusLog(int $priorAuthId): array
    {
        return $this->get("api/priorAuths/{$priorAuthId}/statusLog");
    }

    /**
     * GET /api/priorAuths/{priorAuthId}/questions/{questionId}
     *
     * Get Single Question By Id.
     *
     * @dosespot PriorAuthorizations_GetSingleQuestionDetailsByIdV2
     */
    public function question(int $priorAuthId, int $questionId): array
    {
        return $this->get("api/priorAuths/{$priorAuthId}/questions/{$questionId}");
    }

    /**
     * GET /api/priorAuths/{priorAuthId}/attachments/{attachmentId}
     *
     * Download Attachment.
     *
     * @dosespot PriorAuthorizations_DownloadAttachmentV2
     */
    public function attachment(int $priorAuthId, int $attachmentId): array
    {
        return $this->get("api/priorAuths/{$priorAuthId}/attachments/{$attachmentId}");
    }

    /**
     * DELETE /api/priorAuths/{priorAuthId}/attachments/{attachmentId}
     *
     * Delete an Attachment.
     *
     * @dosespot PriorAuthorizations_DeleteAttachmentV2
     */
    public function deleteAttachment(int $priorAuthId, int $attachmentId): array
    {
        return $this->delete("api/priorAuths/{$priorAuthId}/attachments/{$attachmentId}");
    }

    /**
     * POST /api/priorAuths/initiate
     *
     * Initiate Prior Authorization.
     *
     * @dosespot PriorAuthorizations_InitiatePriorAuthorizationV2
     */
    public function initiate(array $payload): array
    {
        return $this->post('api/priorAuths/initiate', $payload);
    }

    /**
     * POST /api/priorAuths/{priorAuthId}/appeal
     *
     * Appeal Case By Id.
     *
     * @dosespot PriorAuthorizations_AppealCaseV2
     */
    public function appeal(int $priorAuthId, array $payload): array
    {
        return $this->post("api/priorAuths/{$priorAuthId}/appeal", $payload);
    }

    /**
     * POST /api/priorAuths/{priorAuthId}/cancel
     *
     * Cancel PA Case By Id.
     *
     * @dosespot PriorAuthorizations_CancelPriorAuthCaseV2
     */
    public function cancel(int $priorAuthId): array
    {
        return $this->post("api/priorAuths/{$priorAuthId}/cancel");
    }

    /**
     * POST /api/priorAuths/{priorAuthId}/denied
     *
     * Deny Case By Id.
     *
     * @dosespot PriorAuthorizations_DenyPACaseV2
     */
    public function deny(int $priorAuthId): array
    {
        return $this->post("api/priorAuths/{$priorAuthId}/denied");
    }

    /**
     * POST /api/priorAuths/{priorAuthId}/remove
     *
     * Remove PA Case By Id.
     *
     * @dosespot PriorAuthorizations_RemovePriorAuthCaseV2
     */
    public function remove(int $priorAuthId): array
    {
        return $this->post("api/priorAuths/{$priorAuthId}/remove");
    }

    /**
     * POST /api/priorAuths/{priorAuthId}/submit
     *
     * Submit PA Answers.
     *
     * @dosespot PriorAuthorizations_SubmitPAAnswersV2
     */
    public function submit(int $priorAuthId): array
    {
        return $this->post("api/priorAuths/{$priorAuthId}/submit");
    }

    /**
     * POST /api/priorAuths/{priorAuthId}/attachments
     *
     * Upload Attachment. The published spec declares no request body.
     *
     * @dosespot PriorAuthorizations_UploadAttachmentV2
     */
    public function attach(int $priorAuthId): array
    {
        return $this->post("api/priorAuths/{$priorAuthId}/attachments");
    }

    /**
     * POST /api/priorAuths/{priorAuthId}/approveOffline
     *
     * Approve Offline Case By Id.
     *
     * @dosespot PriorAuthorizations_ApproveOfflineCaseV2
     */
    public function approveOffline(int $priorAuthId, array $payload): array
    {
        return $this->post("api/priorAuths/{$priorAuthId}/approveOffline", $payload);
    }

    /**
     * POST /api/priorAuths/{priorAuthId}/questions/{questionId}/answer
     *
     * Answer PA Question.
     *
     * @dosespot PriorAuthorizations_PAAnswerSingleQuestionV2
     */
    public function answer(int $priorAuthId, int $questionId, array $payload): array
    {
        return $this->post("api/priorAuths/{$priorAuthId}/questions/{$questionId}/answer", $payload);
    }
}
