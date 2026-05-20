# dosespot-php

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yannelli/dosespot-php.svg?style=flat-square)](https://packagist.org/packages/yannelli/dosespot-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/yannelli/dosespot-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/yannelli/dosespot-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/yannelli/dosespot-php.svg?style=flat-square)](https://packagist.org/packages/yannelli/dosespot-php)

A modern, framework-agnostic PHP client for the
[DoseSpot Web API](https://my.staging.dosespot.com/webapi/Help). Built on
Guzzle, typed for PHP 8.4, and organized around DoseSpot's natural resource
groupings so the methods read like the documentation.

```php
use Yannelli\DoseSpot\DoseSpot;

$dosespot = DoseSpot::staging(
    clinicId: '12345',
    clinicKey: getenv('DOSESPOT_CLINIC_KEY'),
    userId: 67890,
);

$patient = $dosespot->patients()->create([
    'FirstName' => 'Jane',
    'LastName' => 'Doe',
    'DateOfBirth' => '1990-01-02',
    'Gender' => 2,
    'Address1' => '1 Example St',
    'City' => 'Austin',
    'State' => 'TX',
    'ZipCode' => '78701',
    'PrimaryPhone' => '5125550100',
    'PrimaryPhoneType' => 2,
    'Active' => true,
]);

$dosespot->prescriptions()->createCoded($patient['Id'], [
    'PharmacyId' => 9876,
    'DispensableDrugId' => 12345,
    'Quantity' => 30,
    'DaysSupply' => 30,
    'Refills' => 2,
    'Directions' => 'Take one tablet by mouth daily.',
]);

$dosespot->prescriptions()->send($patient['Id'], $prescriptionId, pin: '123456');
```

## Installation

```bash
composer require yannelli/dosespot-php
```

Requires PHP 8.4+.

## Quick start

The library ships with two factories that target DoseSpot's hosted
environments:

```php
DoseSpot::staging($clinicId, $clinicKey, userId: null);
DoseSpot::production($clinicId, $clinicKey, userId: null);
```

The optional `userId` is forwarded as the `X-DoseSpot-UserId` header when
requesting tokens, which DoseSpot uses to scope the access token to a
particular clinician.

You can also construct the client manually for full control:

```php
use Yannelli\DoseSpot\Config;
use Yannelli\DoseSpot\DoseSpot;
use Yannelli\DoseSpot\Environment;

$dosespot = new DoseSpot(new Config(
    clinicId: '12345',
    clinicKey: $key,
    environment: Environment::Staging,
    userId: 67890,
    timeout: 30,
));
```

To switch users without re-creating the rest of the configuration:

```php
$prescriber = $dosespot->asUser(98765);
```

## Authentication

DoseSpot's `/webapi/token` endpoint accepts an OAuth 2.0 password grant
seeded with the clinic id and a per-request key derived from your clinic
secret. The library performs that handshake transparently:

```php
$token = $dosespot->authenticator->token();   // requests / returns cached
$dosespot->authenticator->forget();           // clear the cache
```

Tokens are reused for the duration of their `expires_in` window with a
30-second leeway, so most callers never need to touch the authenticator
directly.

## Resources

| Resource                    | Accessor                          | Endpoints covered |
| --------------------------- | --------------------------------- | ----------------- |
| Allergies                   | `$dosespot->allergies()`          | 6                 |
| Clinicians                  | `$dosespot->clinicians()`         | 23                |
| Clinics                     | `$dosespot->clinics()`            | 6                 |
| Compounds                   | `$dosespot->compounds()`          | 1                 |
| Diagnosis                   | `$dosespot->diagnosis()`          | 2                 |
| Dispense Units              | `$dosespot->dispenseUnits()`      | 2                 |
| Eligibilities               | `$dosespot->eligibilities()`      | 4                 |
| General (health check)      | `$dosespot->general()`            | 1                 |
| Medications                 | `$dosespot->medications()`        | 5                 |
| Migration                   | `$dosespot->migration()`          | 1                 |
| Notifications               | `$dosespot->notifications()`      | 4                 |
| Patients                    | `$dosespot->patients()`           | 15                |
| Pharmacies                  | `$dosespot->pharmacies()`         | 4                 |
| Prescriptions               | `$dosespot->prescriptions()`      | 30+               |
| Prior Authorization         | `$dosespot->priorAuth()`          | 15                |
| Refills                     | `$dosespot->refills()`            | 10                |
| RxChange                    | `$dosespot->rxChange()`           | 11                |
| Self-Reported Medications   | `$dosespot->selfReportedMedications()` | 10           |
| Supplies                    | `$dosespot->supplies()`           | 1                 |

Each method maps directly to a DoseSpot endpoint. For example:

```php
$dosespot->patients()->search(firstName: 'Jane', lastName: 'Doe');
$dosespot->medications()->search('lisinopril');
$dosespot->pharmacies()->search(city: 'Austin', state: 'TX', specialty: [1, 2]);
$dosespot->prescriptions()->createCoded(5, [...]);
$dosespot->prescriptions()->send(5, 99, pin: '123456');
$dosespot->refills()->approve(123);
$dosespot->rxChange()->reconcile(5, 17);
$dosespot->priorAuth()->initiate(['PatientId' => 5, 'NDC' => '12345']);
```

Body payloads accept plain associative arrays so you can pass any field
DoseSpot exposes, including future additions, without waiting for this
library to add them.

## Error handling

The HTTP client translates non-2xx responses into typed exceptions, all of
which extend `Yannelli\DoseSpot\Exceptions\DoseSpotException`:

| Status | Exception                  |
| ------ | -------------------------- |
| 400/422 | `ValidationException`     |
| 401/403 | `AuthenticationException` |
| 404    | `NotFoundException`        |
| 429    | `RateLimitException` (with `retryAfter()`) |
| 5xx    | `ApiException`             |

```php
use Yannelli\DoseSpot\Exceptions\RateLimitException;
use Yannelli\DoseSpot\Exceptions\ValidationException;

try {
    $dosespot->prescriptions()->send(5, 99);
} catch (RateLimitException $e) {
    sleep($e->retryAfter() ?? 1);
} catch (ValidationException $e) {
    report($e->responseBody());
}
```

## Testing

```bash
composer test
```

The package ships with a Pest test suite that exercises the resource
classes against a mocked Guzzle handler, so no DoseSpot credentials are
needed to run them.

## Changelog

See [CHANGELOG](CHANGELOG.md) for release notes.

## License

The MIT License (MIT). See [License File](LICENSE.md).
