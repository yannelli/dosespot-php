<?php

declare(strict_types=1);

use Yannelli\DoseSpot\DoseSpot;
use Yannelli\DoseSpot\Environment;
use Yannelli\DoseSpot\Resources\Allergies;
use Yannelli\DoseSpot\Resources\Clinicians;
use Yannelli\DoseSpot\Resources\Clinics;
use Yannelli\DoseSpot\Resources\Compounds;
use Yannelli\DoseSpot\Resources\Diagnosis;
use Yannelli\DoseSpot\Resources\DispenseUnits;
use Yannelli\DoseSpot\Resources\Eligibilities;
use Yannelli\DoseSpot\Resources\General;
use Yannelli\DoseSpot\Resources\Medications;
use Yannelli\DoseSpot\Resources\Migration;
use Yannelli\DoseSpot\Resources\Notifications;
use Yannelli\DoseSpot\Resources\Patients;
use Yannelli\DoseSpot\Resources\Pharmacies;
use Yannelli\DoseSpot\Resources\Prescriptions;
use Yannelli\DoseSpot\Resources\PriorAuth;
use Yannelli\DoseSpot\Resources\Refills;
use Yannelli\DoseSpot\Resources\RxChange;
use Yannelli\DoseSpot\Resources\SelfReportedMedications;
use Yannelli\DoseSpot\Resources\Supplies;

it('exposes every resource group', function (string $accessor, string $expected) {
    $client = DoseSpot::staging(clinicId: '1', clinicKey: 'k');

    expect($client->{$accessor}())->toBeInstanceOf($expected);
})->with([
    ['allergies', Allergies::class],
    ['clinicians', Clinicians::class],
    ['clinics', Clinics::class],
    ['compounds', Compounds::class],
    ['diagnosis', Diagnosis::class],
    ['dispenseUnits', DispenseUnits::class],
    ['eligibilities', Eligibilities::class],
    ['general', General::class],
    ['medications', Medications::class],
    ['migration', Migration::class],
    ['notifications', Notifications::class],
    ['patients', Patients::class],
    ['pharmacies', Pharmacies::class],
    ['prescriptions', Prescriptions::class],
    ['priorAuth', PriorAuth::class],
    ['refills', Refills::class],
    ['rxChange', RxChange::class],
    ['selfReportedMedications', SelfReportedMedications::class],
    ['supplies', Supplies::class],
]);

it('chooses the staging environment via the static factory', function () {
    $client = DoseSpot::staging(clinicId: '1', clinicKey: 'k');

    expect($client->config->environment)->toBe(Environment::Staging);
    expect($client->config->baseUrl())->toBe('https://my.staging.dosespot.com/webapi');
});

it('chooses the production environment via the static factory', function () {
    $client = DoseSpot::production(clinicId: '1', clinicKey: 'k');

    expect($client->config->environment)->toBe(Environment::Production);
    expect($client->config->baseUrl())->toBe('https://my.dosespot.com/webapi');
});

it('clones the client with a different user id', function () {
    $client = DoseSpot::staging(clinicId: '1', clinicKey: 'k', userId: 10);

    $next = $client->asUser(20);

    expect($client->config->userId)->toBe(10);
    expect($next->config->userId)->toBe(20);
    expect($next)->not->toBe($client);
});
