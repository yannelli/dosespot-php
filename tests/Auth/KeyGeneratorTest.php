<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Auth\KeyGenerator;

it('builds a 54-character key from the clinic key and seed', function () {
    $seed = str_repeat("\x01", 32);
    $key = (new KeyGenerator())->generate('some-clinic-key', $seed);

    expect($key)->toHaveLength(54);
});

it('produces different keys for different seeds', function () {
    $generator = new KeyGenerator();

    $a = $generator->generate('k', str_repeat("\x01", 32));
    $b = $generator->generate('k', str_repeat("\x02", 32));

    expect($a)->not->toBe($b);
});

it('is deterministic for a given seed and clinic key', function () {
    $generator = new KeyGenerator();
    $seed = str_repeat("\xAA", 32);

    expect($generator->generate('k', $seed))->toBe($generator->generate('k', $seed));
});
