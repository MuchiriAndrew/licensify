# Licensify

A Laravel-based **software licensing API** for validating and managing licenses (e.g. for WordPress plugins, SaaS, or desktop apps). Clients send a license key and domain; the server validates and optionally binds the license to that domain on first use.

---

## Project overview

- **Purpose:** Licensify is a central license validation service so you can sell or gate software by domain.
- **Use cases:** Licensed WordPress plugins (e.g. M-Pesa), SaaS add-ons, domain-locked web apps.
- **Stack:** Laravel 10, Filament 2 (admin), Laravel Sanctum (API auth).

---

## Features

- **License validation API** — `POST /api/validate-license` with `license_key` and `domain`.
- **Domain binding** — First successful validation locks the license to that domain (optional).
- **Admin panel** — Create/edit licenses, toggle active, set expiry; view activation history.
- **Security** — Input validation, domain normalization, generic error messages, rate limiting (10/min on validate).
- **Audit log** — Every validation (success/failure) stored in `license_activations`.
- **WordPress client** — Drop-in PHP helper in `docs/wordpress-license-client.php` with caching.

---

## Tech stack

| Layer        | Technology        |
|-------------|--------------------|
| Framework   | Laravel 10         |
| Admin UI    | Filament 2         |
| API auth    | Laravel Sanctum    |
| Database    | MySQL (configurable)|

---

## Quick start

### Requirements

- PHP 8.1+
- Composer
- MySQL (or SQLite for local)

### Install and run

```bash
git clone <repo-url>
cd licensing-server
cp .env.example .env
php artisan key:generate
# Set DB_* in .env
php artisan migrate
php artisan db:seed   # optional: seeds example license
php artisan serve
```

- **Web:** http://localhost:8000  
- **Admin:** http://localhost:8000/admin (create a user if needed: `php artisan make:filament-user`)  
- **API:** `POST http://localhost:8000/api/validate-license`

### Validate a license (example)

```bash
curl -X POST http://localhost:8000/api/validate-license \
  -H "Accept: application/json" \
  -d "license_key=YOUR-KEY" \
  -d "domain=example.com"
```

Success: `{"status":"success","message":"License validation successful"}`  
Failure: `{"status":"error","message":"License validation failed"}` (HTTP 403)

---

## Documentation

- **[API reference](docs/API.md)** — Endpoints, request/response formats, examples.
- **[Production readiness](docs/PRODUCTION_READINESS_REPORT.md)** — Security and feature checklist.
- **WordPress integration** — Use `docs/wordpress-license-client.php` in your plugin; see comments in file.

---

## Testing

```bash
composer install
cp .env.example .env
php artisan key:generate
# Use SQLite for tests: DB_CONNECTION=sqlite, DB_DATABASE=:memory: in .env or phpunit.xml
php artisan test
```

Feature tests cover: valid license, invalid key, expired/inactive, domain mismatch, validation errors, rate limiting behavior.

---

## Project structure (high level)

```
app/
  Http/Controllers/LicenseController.php   # Validation endpoint
  Http/Requests/ValidateLicenseRequest.php  # Input validation
  Models/License.php, LicenseActivation.php
  Services/LicenseValidationService.php    # Domain normalization, key generation
  Filament/Resources/LicenseResource.php    # Admin CRUD + activation history
routes/api.php                              # validate-license route
docs/
  API.md
  PRODUCTION_READINESS_REPORT.md
  wordpress-license-client.php
```

---

## Portfolio highlights

- **API design** — RESTful validation endpoint, validation layer, consistent JSON responses.
- **Security** — Rate limiting, generic error messages, domain normalization, audit logging.
- **Admin UX** — Filament resource with activation history; auto-generated license keys.
- **Client SDK** — Reusable PHP/WordPress client with caching and clear usage notes.
- **Testing** — Feature tests for the validation flow.
- **Documentation** — README, API docs, and production checklist.

---

## License

MIT (or your chosen license).
