<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

enum HeightMetric: string
{
    case Inches = 'in';
    case Centimeters = 'cm';
    case Meters = 'm';
    case Feet = 'ft';
}
