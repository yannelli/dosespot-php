<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * AddEditPatientRequest.Gender in the Full + EPCS v2 spec.
 */
enum GenderType: string
{
    case Male = 'Male';
    case Female = 'Female';
    case Unknown = 'Unknown';
}
