# AGENTS.md

## Cursor Cloud specific instructions

`yannelli/dosespot-php` is a framework-agnostic PHP 8.4 client library (SDK) for
the DoseSpot e-prescribing Web API. There is no server or UI to run — the
"application" is the library itself, exercised via its test suite and by
consuming it from PHP scripts.

### Toolchain
- Requires PHP 8.4 (installed via the `ondrej/php` PPA) and Composer. Both are
  provided by the VM snapshot; the startup update script only runs
  `composer install`.
- Standard scripts live in `composer.json` (`composer test`, `composer format`).

### Lint / test / build / run
- Test: `composer test` (Pest). Tests use a mocked Guzzle handler, so no
  DoseSpot credentials are required.
- Lint (check only): `vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --allow-risky=yes --dry-run --diff`
- Lint (autofix): `composer format`.
- There is no build step (library only).
- To "run" the library, consume it from a PHP script (see README usage). Real
  API calls need DoseSpot clinic credentials (`clinicId`, `clinicKey`,
  `userId`); without them, drive it against a mocked Guzzle handler as the tests
  in `tests/Support/Factory.php` do.

### Gotchas
- `composer.lock` is gitignored, so `composer install` resolves fresh each time;
  this matches CI, which runs `composer update`.
