<?php

declare(strict_types=1);

use Yannelli\DoseSpot\DoseSpot;
use Yannelli\DoseSpot\Environment;
use Yannelli\DoseSpot\Resources\Allergens;
use Yannelli\DoseSpot\Resources\Allergies;
use Yannelli\DoseSpot\Resources\ClinicFavorites;
use Yannelli\DoseSpot\Resources\ClinicianFavorites;
use Yannelli\DoseSpot\Resources\ClinicianOrderSets;
use Yannelli\DoseSpot\Resources\Clinicians;
use Yannelli\DoseSpot\Resources\ClinicOrderSets;
use Yannelli\DoseSpot\Resources\Clinics;
use Yannelli\DoseSpot\Resources\Diagnoses;
use Yannelli\DoseSpot\Resources\DispenseUnits;
use Yannelli\DoseSpot\Resources\Eligibilities;
use Yannelli\DoseSpot\Resources\General;
use Yannelli\DoseSpot\Resources\Interactions;
use Yannelli\DoseSpot\Resources\MedicationHistory;
use Yannelli\DoseSpot\Resources\Medications;
use Yannelli\DoseSpot\Resources\Narx;
use Yannelli\DoseSpot\Resources\Notifications;
use Yannelli\DoseSpot\Resources\Patients;
use Yannelli\DoseSpot\Resources\Pharmacies;
use Yannelli\DoseSpot\Resources\Prescriptions;
use Yannelli\DoseSpot\Resources\PriorAuth;
use Yannelli\DoseSpot\Resources\Refills;
use Yannelli\DoseSpot\Resources\RxChange;
use Yannelli\DoseSpot\Resources\SelfReportedMedications;
use Yannelli\DoseSpot\Resources\Supplies;
use Yannelli\DoseSpot\Resources\Transparency;

it('exposes every resource group', function (string $accessor, string $expected) {
    $client = DoseSpot::staging(clinicId: '1', clinicKey: 'k', subscriptionKey: 'sub', userId: 7);

    expect($client->{$accessor}())->toBeInstanceOf($expected);
})->with([
    ['allergens', Allergens::class],
    ['allergies', Allergies::class],
    ['clinicians', Clinicians::class],
    ['clinicianFavorites', ClinicianFavorites::class],
    ['clinicianOrderSets', ClinicianOrderSets::class],
    ['clinics', Clinics::class],
    ['clinicFavorites', ClinicFavorites::class],
    ['clinicOrderSets', ClinicOrderSets::class],
    ['diagnoses', Diagnoses::class],
    ['dispenseUnits', DispenseUnits::class],
    ['eligibilities', Eligibilities::class],
    ['general', General::class],
    ['interactions', Interactions::class],
    ['medicationHistory', MedicationHistory::class],
    ['medications', Medications::class],
    ['narx', Narx::class],
    ['notifications', Notifications::class],
    ['patients', Patients::class],
    ['pharmacies', Pharmacies::class],
    ['prescriptions', Prescriptions::class],
    ['priorAuth', PriorAuth::class],
    ['refills', Refills::class],
    ['rxChange', RxChange::class],
    ['selfReportedMedications', SelfReportedMedications::class],
    ['supplies', Supplies::class],
    ['transparency', Transparency::class],
]);

it('chooses the staging environment via the static factory', function () {
    $client = DoseSpot::staging(clinicId: '1', clinicKey: 'k', subscriptionKey: 'sub', userId: 7);

    expect($client->config->environment)->toBe(Environment::Staging);
    expect($client->config->baseUrl)->toBe('https://my.staging.dosespot.com/webapi/v2');
    expect($client->config->userId)->toBe(7);
    expect($client->config->subscriptionKey)->toBe('sub');
});

it('chooses the production environment via the static factory', function () {
    $client = DoseSpot::production(clinicId: '1', clinicKey: 'k', subscriptionKey: 'sub', userId: 7);

    expect($client->config->environment)->toBe(Environment::Production);
    expect($client->config->baseUrl)->toBe('https://my.dosespot.com/webapi/v2');
});

it('targets the Full + EPCS v2 spec', function () {
    expect(DoseSpot::SPEC)->toBe('Full_EPCSV2');
});

it('clones the client with a different user id and shares the Guzzle handler', function () {
    $factory = factory();
    $client = $factory->preauthorizedClient();

    $next = $client->asUser(99);

    expect($next)->not->toBe($client);
    expect($next->config->userId)->toBe(99);
    expect($next->config->subscriptionKey)->toBe($client->config->subscriptionKey);

    $reflection = new ReflectionProperty($client, 'guzzle');
    expect($reflection->getValue($next))->toBe($reflection->getValue($client));
});

it('requests a fresh token when switching users', function () {
    $factory = factory();
    $client = $factory->preauthorizedClient();

    $factory->pushToken('fresh-user-token');
    $factory->pushResponse(200, ['Id' => 1]);

    $client->asUser(99)->general()->check();

    $tokenRequest = $factory->history[0]['request'];
    $apiRequest = $factory->history[1]['request'];
    parse_str((string) $tokenRequest->getBody(), $form);

    expect($tokenRequest->getUri()->getPath())->toBe('/webapi/v2/connect/token');
    expect($form['username'])->toBe('99');
    expect($tokenRequest->getHeaderLine('Subscription-Key'))->toBe('subscription-key');
    expect($apiRequest->getHeaderLine('Authorization'))->toBe('Bearer fresh-user-token');
    expect($apiRequest->getHeaderLine('Subscription-Key'))->toBe('subscription-key');
});
