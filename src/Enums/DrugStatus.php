<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * Medication and supply search status in the Full + EPCS v2 spec.
 */
enum DrugStatus: string
{
    case Active = 'Active';
    case Inactive = 'Inactive';
    case All = 'All';
}
