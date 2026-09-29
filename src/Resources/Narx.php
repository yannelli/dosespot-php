<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Resources;

class Narx extends Resource
{
    /**
     * GET /api/patients/{patientId}/narx
     *
     * Get patient Narx Report.
     *
     * @dosespot Narx_GetPatientNarxReportV2
     */
    public function report(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/narx");
    }

    /**
     * GET /api/patients/{patientId}/narx/latest
     *
     * Get latest patient NARX/PDMP data timestamp.
     *
     * @dosespot Narx_GetLatestNarxV2
     */
    public function latest(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/narx/latest");
    }

    /**
     * GET /api/patients/{patientId}/narx/report
     *
     * Get patient Narx Report Link.
     *
     * @dosespot Narx_GetPatientNarxReportLinkV2
     */
    public function reportLink(int $patientId): array
    {
        return $this->get("api/patients/{$patientId}/narx/report");
    }
}
