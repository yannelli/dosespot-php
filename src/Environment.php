<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot;

enum Environment: string
{
    case Production = 'production';
    case Staging = 'staging';

    public function baseUrl(): string
    {
        return match ($this) {
            self::Production => 'https://my.dosespot.com/webapi/v2',
            self::Staging => 'https://my.staging.dosespot.com/webapi/v2',
        };
    }
}
