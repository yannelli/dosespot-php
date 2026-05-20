<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

enum RefillStatus: int
{
    case Pending = 1;
    case Approved = 2;
    case Denied = 3;
    case Replaced = 4;
}
