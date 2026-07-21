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

it('uses randomBytes for an unspecified seed so subclasses can control entropy', function () {
    $generator = new class () extends KeyGenerator {
        public int $calls = 0;

        protected function randomBytes(int $length): string
        {
            $this->calls++;

            expect($length)->toBe(32);

            return str_repeat("\x03", $length);
        }
    };

    $key = $generator->generate('clinic-key');

    expect($generator->calls)->toBe(1);
    expect($key)->toBe((new KeyGenerator())->generate('clinic-key', str_repeat("\x03", 32)));
});
