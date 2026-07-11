<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Enums\GenderType;
use Yannelli\DoseSpot\Enums\HeightMetric;
use Yannelli\DoseSpot\Enums\PhoneType;
use Yannelli\DoseSpot\Enums\PrescriptionStatus;
use Yannelli\DoseSpot\Enums\RefillStatus;
use Yannelli\DoseSpot\Enums\RxChangeStatus;
use Yannelli\DoseSpot\Enums\WeightMetric;

it('exposes stable integer values for gender type', function () {
    expect(GenderType::Male->value)->toBe(1)
        ->and(GenderType::Female->value)->toBe(2)
        ->and(GenderType::Unknown->value)->toBe(3)
        ->and(GenderType::cases())->toHaveCount(3);
});

it('exposes stable integer values for phone type', function () {
    expect(PhoneType::Beeper->value)->toBe(1)
        ->and(PhoneType::Cell->value)->toBe(2)
        ->and(PhoneType::Fax->value)->toBe(3)
        ->and(PhoneType::Home->value)->toBe(4)
        ->and(PhoneType::Work->value)->toBe(5)
        ->and(PhoneType::Night->value)->toBe(6)
        ->and(PhoneType::Primary->value)->toBe(7)
        ->and(PhoneType::cases())->toHaveCount(7);
});

it('exposes stable integer values for prescription status', function () {
    expect(PrescriptionStatus::Entered->value)->toBe(1)
        ->and(PrescriptionStatus::Printed->value)->toBe(2)
        ->and(PrescriptionStatus::Sending->value)->toBe(3)
        ->and(PrescriptionStatus::ErrorSending->value)->toBe(4)
        ->and(PrescriptionStatus::SentSuccessfully->value)->toBe(5)
        ->and(PrescriptionStatus::Received->value)->toBe(6)
        ->and(PrescriptionStatus::ReceivedWithErrors->value)->toBe(7)
        ->and(PrescriptionStatus::ReadyToSend->value)->toBe(8)
        ->and(PrescriptionStatus::PharmacyVerified->value)->toBe(9)
        ->and(PrescriptionStatus::Deleted->value)->toBe(10)
        ->and(PrescriptionStatus::EditedAndUnsent->value)->toBe(11)
        ->and(PrescriptionStatus::PendingReview->value)->toBe(12)
        ->and(PrescriptionStatus::EpcsError->value)->toBe(13)
        ->and(PrescriptionStatus::Rejected->value)->toBe(14)
        ->and(PrescriptionStatus::EpcsSigned->value)->toBe(15)
        ->and(PrescriptionStatus::cases())->toHaveCount(15);
});

it('exposes stable integer values for refill and rxchange status', function () {
    expect(RefillStatus::Pending->value)->toBe(1)
        ->and(RefillStatus::Approved->value)->toBe(2)
        ->and(RefillStatus::Denied->value)->toBe(3)
        ->and(RefillStatus::Replaced->value)->toBe(4)
        ->and(RefillStatus::cases())->toHaveCount(4);

    expect(RxChangeStatus::Pending->value)->toBe(1)
        ->and(RxChangeStatus::Approved->value)->toBe(2)
        ->and(RxChangeStatus::Denied->value)->toBe(3)
        ->and(RxChangeStatus::cases())->toHaveCount(3);
});

it('exposes stable string values for height and weight metrics', function () {
    expect(HeightMetric::Inches->value)->toBe('in')
        ->and(HeightMetric::Centimeters->value)->toBe('cm')
        ->and(HeightMetric::Meters->value)->toBe('m')
        ->and(HeightMetric::Feet->value)->toBe('ft')
        ->and(HeightMetric::cases())->toHaveCount(4);

    expect(WeightMetric::Pounds->value)->toBe('lb')
        ->and(WeightMetric::Kilograms->value)->toBe('kg')
        ->and(WeightMetric::Ounces->value)->toBe('oz')
        ->and(WeightMetric::Grams->value)->toBe('g')
        ->and(WeightMetric::cases())->toHaveCount(4);
});

it('can resolve every enum case from its raw value', function (string $enumClass) {
    foreach ($enumClass::cases() as $case) {
        expect($enumClass::from($case->value))->toBe($case);
    }
})->with([
    GenderType::class,
    HeightMetric::class,
    PhoneType::class,
    PrescriptionStatus::class,
    RefillStatus::class,
    RxChangeStatus::class,
    WeightMetric::class,
]);
