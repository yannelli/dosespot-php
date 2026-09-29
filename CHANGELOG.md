# Changelog

All notable changes to `dosespot-php` will be documented in this file.

## Unreleased

### Changed

- Target DoseSpot REST API v2 (`/webapi/v2`) and the Full + EPCS contract (`Full_EPCSV2`, 175 operations).
- Request tokens from `POST /webapi/v2/connect/token` with the v2 password grant (`client_id`, `client_secret`, clinician `username`, clinic key as `password`, `scope=api`).
- Send `Subscription-Key` on the token request and on every API call. `subscriptionKey` and `userId` are required configuration.
- Encode list query parameters as repeated keys, matching swagger `collectionFormat: multi`.
- Treat HTTP 200 responses whose `Result.ResultCode` is not `OK` as `ApiException`.
- Replace v1 integer enums with the string enums published in the v2 spec (`GenderType`, `PhoneType`, `PrescriptionStatus`, and the measurement metrics). `HeightMetric` is `inch` / `cm`.

### Added

- Resources for allergens, interactions, medication history, Narx reports, clinician and clinic favorites, order sets, transparency, DEA numbers, and patient diagnoses.
- `PATCH` support and JSON bodies on `DELETE`.

### Removed

- v1 signing-key authentication (`KeyGenerator` and the `X-DoseSpot-UserId` token header).
- v1-only resources and paths that are not in the Full + EPCS v2 spec, including compound search, drug-database migration, and the previous per-prescription send/pin URL shapes. Compiled compounds are created through `prescriptions()->createCompiledCompound()`.
