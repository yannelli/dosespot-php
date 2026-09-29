<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * Prescription.MedicationStatus in the Full + EPCS v2 spec.
 */
enum MedicationStatus: string
{
    case Unknown = 'Unknown';
    case Active = 'Active';
    case Inactive = 'Inactive';
    case Discontinued = 'Discontinued';
    case Deleted = 'Deleted';
    case Completed = 'Completed';
    case CancelRequested = 'CancelRequested';
    case CancelPending = 'CancelPending';
    case Cancelled = 'Cancelled';
    case CancelDenied = 'CancelDenied';
    case Changed = 'Changed';
    case FullFill = 'FullFill';
    case PartialFill = 'PartialFill';
    case NoFill = 'NoFill';
}
