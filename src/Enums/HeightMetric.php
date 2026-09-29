<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * AddEditPatientRequest.HeightMetric in the Full + EPCS v2 spec.
 */
enum HeightMetric: string
{
    case Inch = 'inch';
    case Centimeter = 'cm';
}
