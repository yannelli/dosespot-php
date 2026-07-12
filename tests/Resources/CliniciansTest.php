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

it('looks up a clinician with optional filters and omits null query params', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();

    $client->clinicians()->lookup(npi: '1234567893');
    $uri = (string) $factory->history[0]['request']->getUri();
    expect($uri)->toContain('/api/clinician');
    expect($uri)->toContain('npi=1234567893');
    expect($uri)->not->toContain('dea=');

    $client->clinicians()->lookup(dea: 'AB1234567');
    $uri = (string) $factory->history[1]['request']->getUri();
    expect($uri)->toContain('dea=AB1234567');
    expect($uri)->not->toContain('npi=');

    $client->clinicians()->lookup();
    expect((string) $factory->history[2]['request']->getUri())
        ->toBe('https://my.staging.dosespot.com/webapi/api/clinician');
});

it('finds, creates, patches, and replaces a clinician', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Id' => 5]);
    $factory->pushResponse(200, ['Id' => 5]);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->clinicians()->find(5);
    $client->clinicians()->create(['FirstName' => 'Jane', 'LastName' => 'Doe']);
    $client->clinicians()->update(5, ['FirstName' => 'Janet']);
    $client->clinicians()->replace(5, ['FirstName' => 'Janet', 'LastName' => 'Doe']);

    $methods = array_map(fn ($entry) => $entry['request']->getMethod(), $factory->history);
    expect($methods)->toBe(['GET', 'POST', 'POST', 'PUT']);
    expect($factory->history[0]['request']->getUri()->getPath())->toBe('/webapi/api/clinicians/5');
    expect($factory->history[1]['request']->getUri()->getPath())->toBe('/webapi/api/clinicians');
    expect($factory->history[2]['request']->getUri()->getPath())->toBe('/webapi/api/clinicians/5');
    expect($factory->history[3]['request']->getUri()->getPath())->toBe('/webapi/api/clinicians/5');
});

it('fetches registration status and detailed registration status', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Status' => 'Complete']);
    $factory->pushResponse(200, ['Status' => 'Complete', 'Details' => []]);

    $client = $factory->preauthorizedClient();
    $client->clinicians()->registrationStatus(5);
    $client->clinicians()->registrationStatusDetailed(5);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/5/registrationStatus');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/5/registrationStatusDetailed');
});

it('fetches legal agreements and accepts an agreement', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->clinicians()->legalAgreements(5);
    $client->clinicians()->acceptAgreement(['AgreementId' => 1, 'ClinicianId' => 5]);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/5/legalAgreements');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/acceptAgreement');
    expect($factory->history[1]['request']->getMethod())->toBe('POST');
});

it('fetches pdmp report', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Items' => []]);

    $factory->preauthorizedClient()->clinicians()->pdmp();

    expect($factory->lastRequest()->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/pdmp');
});

it('gets and accepts idp disclaimer', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->clinicians()->idpDisclaimer(5);
    $client->clinicians()->acceptIdpDisclaimer(['ClinicianId' => 5]);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/5/idpDisclaimer');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/idpDisclaimer');
    expect($factory->history[1]['request']->getMethod())->toBe('POST');
});

it('checks idp status by session id', function () {
    $factory = factory();
    $factory->pushResponse(200, ['Status' => 'InProgress']);

    $factory->preauthorizedClient()->clinicians()->idpStatus('abc-123');

    $uri = (string) $factory->lastRequest()->getUri();
    expect($uri)->toContain('/api/clinicians/idpStatus');
    expect($uri)->toContain('sessionId=abc-123');
});

it('activates and deactivates two-factor auth', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->clinicians()->activateTfa(['ClinicianId' => 5]);
    $client->clinicians()->deactivateTfa(['ClinicianId' => 5]);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/tfaActivate');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/tfaDeactivate');
});

it('sets and changes pin', function () {
    $factory = factory();
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->clinicians()->setPin(['ClinicianId' => 5, 'Pin' => '1234']);
    $client->clinicians()->changePin(['ClinicianId' => 5, 'OldPin' => '1234', 'NewPin' => '5678']);

    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/setPin');
    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/changePin');
});

it('initializes and runs the idp challenge flow', function () {
    $factory = factory();
    $factory->pushResponse(200, ['SessionId' => 'sess-1']);
    $factory->pushResponse(200, ['Questions' => []]);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->clinicians()->initIdp(5, ['RedirectUrl' => 'https://example.test/callback']);
    $client->clinicians()->idp(['SessionId' => 'sess-1']);
    $client->clinicians()->submitIdpAnswers(['SessionId' => 'sess-1', 'Answers' => ['A']]);
    $client->clinicians()->submitIdpOtp(['SessionId' => 'sess-1', 'Otp' => '123456']);

    expect($factory->history[0]['request']->getMethod())->toBe('POST');
    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/5/initIdp');
    expect(json_decode((string) $factory->history[0]['request']->getBody(), true))
        ->toBe(['RedirectUrl' => 'https://example.test/callback']);

    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/idp');
    expect(json_decode((string) $factory->history[1]['request']->getBody(), true))
        ->toBe(['SessionId' => 'sess-1']);

    expect($factory->history[2]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/idpAnswers');
    expect(json_decode((string) $factory->history[2]['request']->getBody(), true))
        ->toBe(['SessionId' => 'sess-1', 'Answers' => ['A']]);

    expect($factory->history[3]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/idpOtp');
    expect(json_decode((string) $factory->history[3]['request']->getBody(), true))
        ->toBe(['SessionId' => 'sess-1', 'Otp' => '123456']);
});

it('requests duo activation, initializes tfa steps, and resyncs a token', function () {
    $factory = factory();
    $factory->pushResponse(200, ['ActivationCode' => 'duo-1']);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);
    $factory->pushResponse(200, []);

    $client = $factory->preauthorizedClient();
    $client->clinicians()->requestDuoMobileActivation(['ClinicianId' => 5]);
    $client->clinicians()->initTfaActivate(5, ['Device' => 'phone']);
    $client->clinicians()->initTfaDeactivate(5, ['Reason' => 'lost']);
    $client->clinicians()->resyncToken(['ClinicianId' => 5, 'Token' => '987654']);

    expect($factory->history[0]['request']->getMethod())->toBe('POST');
    expect($factory->history[0]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/requestDuoMobileActivation');
    expect(json_decode((string) $factory->history[0]['request']->getBody(), true))
        ->toBe(['ClinicianId' => 5]);

    expect($factory->history[1]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/5/initTfaActivate');
    expect(json_decode((string) $factory->history[1]['request']->getBody(), true))
        ->toBe(['Device' => 'phone']);

    expect($factory->history[2]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/5/initTfaDeactivate');
    expect(json_decode((string) $factory->history[2]['request']->getBody(), true))
        ->toBe(['Reason' => 'lost']);

    expect($factory->history[3]['request']->getUri()->getPath())
        ->toBe('/webapi/api/clinicians/resyncToken');
    expect(json_decode((string) $factory->history[3]['request']->getBody(), true))
        ->toBe(['ClinicianId' => 5, 'Token' => '987654']);
});
