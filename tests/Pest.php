<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Tests\Support\Factory;

pest()->extends(Yannelli\DoseSpot\Tests\TestCase::class)->in(__DIR__);

function factory(): Factory
{
    return new Factory();
}
