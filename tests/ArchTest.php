<?php

declare(strict_types=1);

it('does not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->not->toBeUsed();

it('uses strict types in src')
    ->expect('Yannelli\DoseSpot')
    ->toUseStrictTypes();
