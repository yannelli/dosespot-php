<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

enum PhoneType: int
{
    case Beeper = 1;
    case Cell = 2;
    case Fax = 3;
    case Home = 4;
    case Work = 5;
    case Night = 6;
    case Primary = 7;
}
