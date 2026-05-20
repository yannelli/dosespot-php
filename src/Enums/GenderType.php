<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

enum GenderType: int
{
    case Male = 1;
    case Female = 2;
    case Unknown = 3;
}
