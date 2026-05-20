<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

enum PrescriptionStatus: int
{
    case Entered = 1;
    case Printed = 2;
    case Sending = 3;
    case ErrorSending = 4;
    case SentSuccessfully = 5;
    case Received = 6;
    case ReceivedWithErrors = 7;
    case ReadyToSend = 8;
    case PharmacyVerified = 9;
    case Deleted = 10;
    case EditedAndUnsent = 11;
    case PendingReview = 12;
    case EpcsError = 13;
    case Rejected = 14;
    case EpcsSigned = 15;
}
