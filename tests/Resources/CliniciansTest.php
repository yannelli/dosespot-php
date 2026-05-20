<?php

declare(strict_types=1);

it('assigns clinics to a clinician', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->clinicians()->addClinics(11, [1, 2, 3]);

    $request = $factory->lastRequest();
    expect($request->getUri()->getPath())->toBe('/webapi/api/clinicians/11/clinics');
    expect(json_decode((string) $request->getBody(), true))->toBe(['ClinicIds' => [1, 2, 3]]);
});

it('looks up a clinician by npi and dea', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->clinicians()->lookup(npi: '1234567893', dea: 'AB1234567');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('npi=1234567893');
    expect($uri)->toContain('dea=AB1234567');
});
