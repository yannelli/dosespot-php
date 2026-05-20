<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

enum WeightMetric: string
{
    case Pounds = 'lb';
    case Kilograms = 'kg';
    case Ounces = 'oz';
    case Grams = 'g';
}
