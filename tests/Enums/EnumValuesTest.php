<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Enums\DrugStatus;
use Yannelli\DoseSpot\Enums\GenderType;
use Yannelli\DoseSpot\Enums\HeightMetric;
use Yannelli\DoseSpot\Enums\MedicationStatus;
use Yannelli\DoseSpot\Enums\PatientStatus;
use Yannelli\DoseSpot\Enums\PharmacySpecialty;
use Yannelli\DoseSpot\Enums\PhoneType;
use Yannelli\DoseSpot\Enums\PrescriptionStatus;
use Yannelli\DoseSpot\Enums\RefillDenialReason;
use Yannelli\DoseSpot\Enums\RxChangeDenialReason;
use Yannelli\DoseSpot\Enums\WeightMetric;

it('matches the Full + EPCS v2 gender, phone, and measurement enums', function () {
    expect(enumValues(GenderType::class))->toBe(['Male', 'Female', 'Unknown']);
    expect(enumValues(PhoneType::class))->toBe([
        'Undefined', 'Beeper', 'Cell', 'Fax', 'Home', 'Work', 'Night', 'Primary',
    ]);
    expect(enumValues(HeightMetric::class))->toBe(['inch', 'cm']);
    expect(enumValues(WeightMetric::class))->toBe(['lb', 'kg']);
});

it('matches the Full + EPCS v2 prescription and medication status enums', function () {
    expect(enumValues(PrescriptionStatus::class))->toBe([
        'Unknown', 'Entered', 'Printed', 'Sending', 'eRxSent', 'FaxSent', 'Error', 'Deleted',
        'Requested', 'Edited', 'EpcsError', 'EpcsSigned', 'ReadyToSign', 'PharmacyVerified',
        'PharmacySelect', 'Retracted',
    ]);
    expect(enumValues(MedicationStatus::class))->toBe([
        'Unknown', 'Active', 'Inactive', 'Discontinued', 'Deleted', 'Completed',
        'CancelRequested', 'CancelPending', 'Cancelled', 'CancelDenied', 'Changed',
        'FullFill', 'PartialFill', 'NoFill',
    ]);
});

it('matches the Full + EPCS v2 search and denial enums', function () {
    expect(enumValues(PatientStatus::class))->toBe(['InactiveOnly', 'ActiveOnly', 'Both']);
    expect(enumValues(DrugStatus::class))->toBe(['Active', 'Inactive', 'All']);
    expect(enumValues(PharmacySpecialty::class))->toBe([
        'Any', 'FaxPharmacy', 'EPCS', 'TwentyFourHourPharmacy', 'LongTermCarePharmacy',
        'MailOrder', 'Retail', 'SpecialtyPharmacy',
    ]);
    expect(enumValues(RefillDenialReason::class))->toBe([
        'DeniedPatientUnknown', 'DeniedPatientNotUnderCare', 'DeniedPatientNoLongerUnderPatientCare',
        'DeniedTooSoon', 'DeniedNeverPrescribed', 'DeniedHavePatientContact', 'DeniedRefillInappropriate',
        'DeniedAlreadyPickedUp', 'DeniedAlreadyPickedUpPartialFill', 'DeniedNotPickedUp',
        'DeniedChangeInappropriate', 'DeniedNeedAppointment', 'DeniedPrescriberNotAssociateWithLocation',
        'DeniedNoPriorAuthAttempt', 'DeniedAlreadyHandled', 'DeniedAtPatientRequest',
        'DeniedPatientAllergicToRequestMed', 'DeniedMedicationDiscontinued',
    ]);
    expect(enumValues(RxChangeDenialReason::class))->toBe([
        'DeniedPatientUnknown', 'DeniedPatientNotUnderCare', 'DeniedPatientNoLongerUnderPatientCare',
        'DeniedNeverPrescribed', 'DeniedHavePatientContact', 'DeniedChangeInappropriate',
        'DeniedNeedAppointment', 'DeniedPrescriberNotAssociateWithLocation', 'DeniedNoPriorAuthAttempt',
        'DeniedAlreadyHandled', 'DeniedAtPatientRequest', 'DeniedPriorAuthNoAttempt',
        'DeniedPriorAuthInProgress', 'DeniedOrderNotFound', 'DeniedPayerNoPriorAuth',
        'DeniedPatientNoRecord', 'DeniedOrderExpired', 'DeniedPharmacyNotRecognized',
    ]);
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
    MedicationStatus::class,
    PatientStatus::class,
    DrugStatus::class,
    PharmacySpecialty::class,
    RefillDenialReason::class,
    RxChangeDenialReason::class,
    WeightMetric::class,
]);

function enumValues(string $enumClass): array
{
    return array_map(static fn (BackedEnum $case): string => (string) $case->value, $enumClass::cases());
}
