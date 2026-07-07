<?php

declare(strict_types=1);

it('fetches patient eligibilities', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->eligibilities()->forPatient(5);

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/eligibilities');
});

it('queries therapeutic alternatives', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->eligibilities()
        ->therapeuticAlternatives(5, 12, '00378511005');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/patients/5/therapeuticAlternatives');
    expect($uri)->toContain('patientEligibilityId=12');
    expect($uri)->toContain('ndc=00378511005');
});

it('queries prescription benefits', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->eligibilities()->prescriptionBenefits(
        patientId: 5,
        ndc: '00378511005',
        pharmacyId: 9876,
        quantity: 30,
        daysSupply: 30,
        dispenseUnitTypeId: 26,
        patientEligibilityId: 12,
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('quantity=30');
    expect($uri)->toContain('daysSupply=30');
    expect($uri)->toContain('dispenseUnitTypeID=26');
    expect($uri)->toContain('patientEligibilityId=12');
    expect($uri)->toContain('pharmacyId=9876');
});

it('queries the formulary for a patient eligibility and ndc', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->eligibilities()->formulary(
        patientId: 5,
        patientEligibilityId: 12,
        ndc: '00378511005',
    );

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/patients/5/formulary');
    expect($uri)->toContain('patientEligibilityId=12');
    expect($uri)->toContain('ndc=00378511005');
});
