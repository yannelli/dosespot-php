<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * PatientsSearchRequest.Status in the Full + EPCS v2 spec.
 */
enum PatientStatus: string
{
    case InactiveOnly = 'InactiveOnly';
    case ActiveOnly = 'ActiveOnly';
    case Both = 'Both';
}
