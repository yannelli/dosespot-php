<?php

declare(strict_types=1);

it('creates the three flavors of self-reported medications', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 1]);
    $factory->pushResponse(200, ['Id' => 2]);
    $factory->pushResponse(200, ['Id' => 3]);

    $client = $factory->preauthorizedClient();
    $client->selfReportedMedications()->createCoded(5, ['DispensableDrugId' => 1]);
    $client->selfReportedMedications()->createSimple(5, ['Name' => 'aspirin']);
    $client->selfReportedMedications()->createFreetext(5, ['DisplayName' => 'aspirin']);

    $paths = array_map(
        fn ($entry) => $entry['request']->getUri()->getPath(),
        $factory->history,
    );

    expect($paths)->toBe([
        '/webapi/api/patients/5/selfReportedMedications/coded',
        '/webapi/api/patients/5/selfReportedMedications/simple',
        '/webapi/api/patients/5/selfReportedMedications/freetext',
    ]);
});

it('replaces a coded self-reported medication via PUT', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->selfReportedMedications()
        ->replaceCoded(5, 17, ['DispensableDrugId' => 2]);

    expect($factory->lastRequest()->getMethod())->toBe('PUT');
    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/selfReportedMedications/coded/17');
});

it('updates a self-reported medication status', function () {
    $factory = factory();
    $factory->pushResponse(200, []);

    $factory->preauthorizedClient()->selfReportedMedications()
        ->updateStatus(5, 17, ['Status' => 'Inactive']);

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/patients/5/selfReportedMedications/17/updateStatus');
});
