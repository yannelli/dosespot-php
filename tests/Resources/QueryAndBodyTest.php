<?php

declare(strict_types=1);

use Yannelli\DoseSpot\Enums\DrugStatus;
use Yannelli\DoseSpot\Enums\PatientStatus;
use Yannelli\DoseSpot\Enums\PharmacySpecialty;
use Yannelli\DoseSpot\Enums\PrescriptionStatus;

it('searches patients with the v2 query names and skips nulls', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->patients()->search(
        firstName: 'Jane',
        lastName: 'Doe',
        dob: new DateTimeImmutable('1990-01-02'),
        status: PatientStatus::ActiveOnly,
        pageNumber: 2,
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/webapi/v2/api/patients/search');
    expect($uri)->toContain('firstName=Jane');
    expect($uri)->toContain('lastName=Doe');
    expect($uri)->toContain('dob=1990-01-02T00%3A00%3A00');
    expect($uri)->toContain('status=ActiveOnly');
    expect($uri)->toContain('pageNumber=2');
});

it('omits null patient search filters', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->patients()->search(lastName: 'Doe');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('lastName=Doe');
    expect($uri)->not->toContain('firstName=');
    expect($uri)->not->toContain('dob=');
    expect($uri)->not->toContain('status=');
});

it('repeats pharmacy specialty query keys', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->pharmacies()->search(
        city: 'Austin',
        state: 'TX',
        specialty: [PharmacySpecialty::Retail, PharmacySpecialty::MailOrder],
        ncpdpId: '1234567',
    );

    $query = $factory->lastRequest()->getUri()->getQuery();
    expect($query)->toContain('city=Austin');
    expect($query)->toContain('state=TX');
    expect($query)->toContain('specialty=Retail');
    expect($query)->toContain('specialty=MailOrder');
    expect($query)->toContain('ncpdpId=1234567');
    expect($query)->not->toContain('specialty%5B');
});

it('uses the swagger NDC query names', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);
    $factory->pushResponse(200, ['Items' => []]);
    $factory->pushResponse(200, ['Items' => []]);

    $client = $factory->preauthorizedClient();
    $client->eligibilities()->formulary(5, 9, '00000-0000-00');
    $client->medications()->select(dispensableDrugId: 15, ndc: '11111-1111-11', rxcui: 99);
    $client->transparency()->alternativePharmacies(5, '22222-2222-22');

    expect($factory->history[0]['request']->getUri()->getQuery())->toContain('nDC=00000-0000-00');
    expect($factory->history[0]['request']->getUri()->getQuery())->toContain('patientEligibilityId=9');
    expect($factory->history[1]['request']->getUri()->getQuery())->toContain('nDC=11111-1111-11');
    expect($factory->history[1]['request']->getUri()->getQuery())->toContain('rXCUI=99');
    expect($factory->history[1]['request']->getUri()->getQuery())->toContain('dispensableDrugId=15');
    expect($factory->history[2]['request']->getUri()->getQuery())->toContain('nDC=22222-2222-22');
});

it('sends prescription benefit filters with the documented query names', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->eligibilities()->prescriptionBenefits(
        patientId: 5,
        ndc: '33333-3333-33',
        pharmacyId: 8,
        quantity: 30,
        daysSupply: 30,
        dispenseUnitTypeId: 26,
    );

    $query = $factory->lastRequest()->getUri()->getQuery();
    expect($query)->toContain('ndc=33333-3333-33');
    expect($query)->toContain('pharmacyId=8');
    expect($query)->toContain('quantity=30');
    expect($query)->toContain('daysSupply=30');
    expect($query)->toContain('dispenseUnitTypeID=26');
    expect($query)->not->toContain('patientEligibilityId=');
});

it('builds the documented JSON bodies for id-style commands', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Result' => ['ResultCode' => 'OK']]);
    $factory->pushResponse(200, ['Result' => ['ResultCode' => 'OK']]);
    $factory->pushResponse(200, ['Result' => ['ResultCode' => 'OK']]);
    $factory->pushResponse(200, ['Result' => ['ResultCode' => 'OK']]);
    $factory->pushResponse(200, ['Result' => ['ResultCode' => 'OK']]);

    $client = $factory->preauthorizedClient();
    $client->patients()->setSsn(5, '123-45-6789');
    $client->pharmacies()->addToPatient(5, 9, setAsPrimary: true);
    $client->prescriptions()->changePharmacy(5, 7, 9);
    $client->refills()->changePatient(4, 5);
    $client->rxChange()->reconcile(4, 5, 7);

    expect(json_decode((string) $factory->history[0]['request']->getBody(), true))->toBe([
        'PatientSSN' => '123-45-6789',
    ]);
    expect($factory->history[0]['request']->getMethod())->toBe('PUT');

    expect(json_decode((string) $factory->history[1]['request']->getBody(), true))->toBe([
        'PharmacyId' => 9,
        'SetAsPrimary' => true,
    ]);
    expect($factory->history[1]['request']->getMethod())->toBe('POST');
    expect($factory->history[1]['request']->getUri()->getPath())->toBe('/webapi/v2/api/patients/5/pharmacies');

    expect($factory->history[2]['request']->getMethod())->toBe('PATCH');
    expect(json_decode((string) $factory->history[2]['request']->getBody(), true))->toBe([
        'PharmacyId' => 9,
    ]);

    expect($factory->history[3]['request']->getMethod())->toBe('PATCH');
    expect(json_decode((string) $factory->history[3]['request']->getBody(), true))->toBe([
        'PatientId' => 5,
    ]);

    expect($factory->history[4]['request']->getMethod())->toBe('POST');
    expect($factory->history[4]['request']->getUri()->getPath())
        ->toBe('/webapi/v2/api/rxchanges/4/patients/5/reconcile');
    expect(json_decode((string) $factory->history[4]['request']->getBody(), true))->toBe([
        'ReferencedPrescriptionId' => 7,
    ]);
});

it('sends medication search status as the documented enum', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->medications()->search('lisinopril', DrugStatus::Active, 3);

    $query = $factory->lastRequest()->getUri()->getQuery();
    expect($query)->toContain('name=lisinopril');
    expect($query)->toContain('drugStatus=Active');
    expect($query)->toContain('pageNumber=3');
});

it('lists patient prescriptions with the documented filters', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->prescriptions()->forPatient(
        patientId: 5,
        startDate: '2026-01-01',
        endDate: '2026-02-01',
        prescriptionStatus: PrescriptionStatus::ERxSent,
        sortColumn: 'DateWritten',
        sortOrder: 'Desc',
    );

    $query = $factory->lastRequest()->getUri()->getQuery();
    expect($factory->lastRequest()->getUri()->getPath())->toBe('/webapi/v2/api/patients/5/prescriptions');
    expect($query)->toContain('startDate=2026-01-01');
    expect($query)->toContain('endDate=2026-02-01');
    expect($query)->toContain('prescriptionStatus=eRxSent');
    expect($query)->toContain('sortColumn=DateWritten');
    expect($query)->toContain('sortOrder=Desc');
});

it('sends delete requests that carry a JSON body', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Result' => ['ResultCode' => 'OK']]);

    $payload = ['PrescriptionIds' => [7, 8]];
    $factory->preauthorizedClient()->prescriptions()->deleteMany(5, $payload);

    $request = $factory->lastRequest();
    expect($request->getMethod())->toBe('DELETE');
    expect($request->getUri()->getPath())->toBe('/webapi/v2/api/patients/5/prescriptions');
    expect(json_decode((string) $request->getBody(), true))->toBe($payload);
});

it('posts bodyless commands without a JSON payload', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Result' => ['ResultCode' => 'OK']]);
    $factory->pushResponse(200, ['Result' => ['ResultCode' => 'OK']]);

    $client = $factory->preauthorizedClient();
    $client->allergies()->createNoKnown(5);
    $client->clinicians()->resetMyPin();

    foreach ([0, 1] as $index) {
        expect((string) $factory->history[$index]['request']->getBody())->toBe('');
        expect($factory->history[$index]['options'])->not->toHaveKey('json');
    }
});
