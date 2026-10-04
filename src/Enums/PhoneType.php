<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * PrimaryPhoneType in the Full + EPCS v2 spec.
 */
enum PhoneType: string
{
    case Undefined = 'Undefined';
    case Beeper = 'Beeper';
    case Cell = 'Cell';
    case Fax = 'Fax';
    case Home = 'Home';
    case Work = 'Work';
    case Night = 'Night';
    case Primary = 'Primary';
}
