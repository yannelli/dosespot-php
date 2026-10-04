<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * AddEditPatientRequest.WeightMetric in the Full + EPCS v2 spec.
 */
enum WeightMetric: string
{
    case Pound = 'lb';
    case Kilogram = 'kg';
}
