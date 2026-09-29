# dosespot-php

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yannelli/dosespot-php.svg?style=flat-square)](https://packagist.org/packages/yannelli/dosespot-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/yannelli/dosespot-php/run-tests-pest.yml?branch=main&label=tests&style=flat-square)](https://github.com/yannelli/dosespot-php/actions/workflows/run-tests-pest.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/yannelli/dosespot-php.svg?style=flat-square)](https://packagist.org/packages/yannelli/dosespot-php)

A modern, framework-agnostic PHP client for the
[DoseSpot REST API v2](https://my.dosespot.com/webapi/v2/swagger/docs/Full_EPCSV2).
Built on Guzzle, typed for PHP 8.4, and organized around DoseSpot's resource
groups so each method maps to one operation in the Full + EPCS v2 contract.

```php
use Yannelli\DoseSpot\DoseSpot;
use Yannelli\DoseSpot\Enums\GenderType;
use Yannelli\DoseSpot\Enums\PhoneType;

$dosespot = DoseSpot::staging(
    clinicId: '12345',
    clinicKey: getenv('DOSESPOT_CLINIC_KEY'),
    subscriptionKey: getenv('DOSESPOT_SUBSCRIPTION_KEY'),
    userId: 67890,
);

$patient = $dosespot->patients()->create([
    'FirstName' => 'Jane',
    'LastName' => 'Doe',
    'DateOfBirth' => '1990-01-02',
    'Gender' => GenderType::Female,
    'Address1' => '1 Example St',
    'City' => 'Austin',
    'State' => 'TX',
    'ZipCode' => '78701',
    'PrimaryPhone' => '5125550100',
    'PrimaryPhoneType' => PhoneType::Cell,
    'Active' => true,
]);

$prescription = $dosespot->prescriptions()->createCoded($patient['Id'], [
    'PharmacyId' => 9876,
    'DispensableDrugId' => 12345,
    'Quantity' => 30,
    'DaysSupply' => 30,
    'Refills' => 2,
    'Directions' => 'Take one tablet by mouth daily.',
]);

$dosespot->prescriptions()->send($patient['Id'], [
    'PrescriptionIds' => [$prescription['Id']],
    'Pin' => '123456',
]);
```

## Installation

```bash
composer require yannelli/dosespot-php
```

Requires PHP 8.4+.

## Quick start

The library ships with two factories that target DoseSpot's hosted
environments. Both use the `/webapi/v2` base path:

```php
DoseSpot::staging($clinicId, $clinicKey, $subscriptionKey, $userId);
DoseSpot::production($clinicId, $clinicKey, $subscriptionKey, $userId);
```

`$userId` is the clinician id. DoseSpot's v2 token endpoint requires it as
the password-grant `username`. `$subscriptionKey` is sent as the
`Subscription-Key` header on the token request and on every API call.

You can also construct the client manually:

```php
use Yannelli\DoseSpot\Config;
use Yannelli\DoseSpot\DoseSpot;
use Yannelli\DoseSpot\Environment;

$dosespot = new DoseSpot(new Config(
    clinicId: '12345',
    clinicKey: getenv('DOSESPOT_CLINIC_KEY'),
    subscriptionKey: getenv('DOSESPOT_SUBSCRIPTION_KEY'),
    userId: 67890,
    environment: Environment::Staging,
    timeout: 30,
));
```

To switch clinicians without re-creating the rest of the configuration:

```php
$prescriber = $dosespot->asUser(98765);
```

## Authentication

`POST /webapi/v2/connect/token` is an OAuth 2.0 password grant. The `password`
field is the clinic key, not a user password:

| Form field | Value |
| --- | --- |
| `grant_type` | `password` |
| `client_id` | clinic id |
| `client_secret` | clinic key |
| `username` | clinician user id |
| `password` | clinic key |
| `scope` | `api` |

```php
$token = $dosespot->authenticator->token();   // requests / returns cached
$dosespot->authenticator->forget();           // clear the cache
```

Tokens are reused for the duration of their `expires_in` window with a
30-second leeway, so most callers never need to touch the authenticator
directly.

## Resources

The client covers all 175 operations in DoseSpot's Full + EPCS v2 swagger
(`Full_EPCSV2`). Accounts on a smaller plan receive DoseSpot's normal
authorization errors for operations that plan does not include.

| Resource | Accessor | Operations |
| --- | --- | --- |
| Allergens | `$dosespot->allergens()` | 3 |
| Allergies | `$dosespot->allergies()` | 5 |
| Clinicians (including DEA numbers) | `$dosespot->clinicians()` | 33 |
| Clinician favorites | `$dosespot->clinicianFavorites()` | 8 |
| Clinician order sets | `$dosespot->clinicianOrderSets()` | 5 |
| Clinics (including clinic groups) | `$dosespot->clinics()` | 9 |
| Clinic favorites | `$dosespot->clinicFavorites()` | 7 |
| Clinic order sets | `$dosespot->clinicOrderSets()` | 1 |
| Diagnoses | `$dosespot->diagnoses()` | 6 |
| Dispense units | `$dosespot->dispenseUnits()` | 2 |
| Eligibilities | `$dosespot->eligibilities()` | 4 |
| General (health check) | `$dosespot->general()` | 1 |
| Interactions | `$dosespot->interactions()` | 3 |
| Medication history | `$dosespot->medicationHistory()` | 2 |
| Medications | `$dosespot->medications()` | 4 |
| Narx | `$dosespot->narx()` | 3 |
| Notifications | `$dosespot->notifications()` | 2 |
| Patients | `$dosespot->patients()` | 7 |
| Pharmacies | `$dosespot->pharmacies()` | 6 |
| Prescriptions | `$dosespot->prescriptions()` | 24 |
| Prior authorization | `$dosespot->priorAuth()` | 16 |
| Refills | `$dosespot->refills()` | 6 |
| RxChange | `$dosespot->rxChange()` | 6 |
| Self-reported medications | `$dosespot->selfReportedMedications()` | 9 |
| Supplies | `$dosespot->supplies()` | 2 |
| Transparency | `$dosespot->transparency()` | 1 |

```php
$dosespot->patients()->search(firstName: 'Jane', lastName: 'Doe');
$dosespot->medications()->search('lisinopril');
$dosespot->pharmacies()->search(city: 'Austin', state: 'TX', specialty: ['Retail', 'MailOrder']);
$dosespot->prescriptions()->createCoded(5, [...]);
$dosespot->prescriptions()->send(5, ['PrescriptionIds' => [99], 'Pin' => '123456']);
$dosespot->refills()->approve(123, ['Refills' => 2]);
$dosespot->rxChange()->reconcile(17, 5, referencedPrescriptionId: 88);
$dosespot->priorAuth()->initiate(['PrescriptionId' => 99]);
```

Body payloads accept plain associative arrays so you can pass any field
DoseSpot exposes, including future additions, without waiting for this
library to add them. Enum cases such as `GenderType` and `PrescriptionStatus`
serialize to the string values in the v2 spec.

Pharmacy specialty filters are repeated query keys (`specialty=Retail&specialty=MailOrder`),
which is the swagger `collectionFormat: multi` encoding.

## Error handling

The HTTP client translates non-2xx responses into typed exceptions, all of
which extend `Yannelli\DoseSpot\Exceptions\DoseSpotException`:

| Status | Exception |
| --- | --- |
| 400/422 | `ValidationException` |
| 401/403 | `AuthenticationException` |
| 404 | `NotFoundException` |
| 429 | `RateLimitException` (with `retryAfter()`) |
| 5xx | `ApiException` |

DoseSpot also returns HTTP 200 with `Result.ResultCode` set to a value other
than `OK` when the call is rejected. Those responses are raised as
`ApiException`, using `Result.ResultDescription` when it is present.

```php
use Yannelli\DoseSpot\Exceptions\ApiException;
use Yannelli\DoseSpot\Exceptions\RateLimitException;
use Yannelli\DoseSpot\Exceptions\ValidationException;

try {
    $dosespot->prescriptions()->send(5, ['PrescriptionIds' => [99]]);
} catch (RateLimitException $e) {
    sleep($e->retryAfter() ?? 1);
} catch (ValidationException|ApiException $e) {
    report($e->responseBody());
}
```

## Testing

```bash
composer test
```

The package ships with a Pest test suite that exercises every Full + EPCS v2
operation against a mocked Guzzle handler, so no DoseSpot credentials are
needed to run them. `tests/Fixtures/full-epcs-v2-operations.json` is the
operation catalog taken from
`https://my.dosespot.com/webapi/v2/swagger/docs/Full_EPCSV2`.

## Changelog

See [CHANGELOG](CHANGELOG.md) for release notes.

## License

The MIT License (MIT). See [License File](LICENSE.md).
