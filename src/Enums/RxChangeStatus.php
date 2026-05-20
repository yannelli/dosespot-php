<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

enum RxChangeStatus: int
{
    case Pending = 1;
    case Approved = 2;
    case Denied = 3;
}
