<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * Pharmacy search specialty values. Sent as repeated query keys.
 */
enum PharmacySpecialty: string
{
    case Any = 'Any';
    case FaxPharmacy = 'FaxPharmacy';
    case EPCS = 'EPCS';
    case TwentyFourHourPharmacy = 'TwentyFourHourPharmacy';
    case LongTermCarePharmacy = 'LongTermCarePharmacy';
    case MailOrder = 'MailOrder';
    case Retail = 'Retail';
    case SpecialtyPharmacy = 'SpecialtyPharmacy';
}
