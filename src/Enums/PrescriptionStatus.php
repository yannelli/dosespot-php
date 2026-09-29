<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * Prescription transmission status from the Full + EPCS v2 spec.
 *
 * Includes Retracted, which the prescription-list query accepts, and Unknown,
 * which prescription records return.
 */
enum PrescriptionStatus: string
{
    case Unknown = 'Unknown';
    case Entered = 'Entered';
    case Printed = 'Printed';
    case Sending = 'Sending';
    case ERxSent = 'eRxSent';
    case FaxSent = 'FaxSent';
    case Error = 'Error';
    case Deleted = 'Deleted';
    case Requested = 'Requested';
    case Edited = 'Edited';
    case EpcsError = 'EpcsError';
    case EpcsSigned = 'EpcsSigned';
    case ReadyToSign = 'ReadyToSign';
    case PharmacyVerified = 'PharmacyVerified';
    case PharmacySelect = 'PharmacySelect';
    case Retracted = 'Retracted';
}
