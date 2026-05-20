<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Tests\Support\Factory;
use Yannelli\DoseSpot\Tests\TestCase;

pest()->extends(TestCase::class)->in(__DIR__);

function factory(): Factory
{
    return new Factory;
}
