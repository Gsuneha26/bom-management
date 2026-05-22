# BOM System — README

This repository contains the Bill of Materials (BOM) Management Module built with Laravel. It provides:

- BOM upload (Excel / CSV) and parsing
- Inventory validation (queued background processing)
- Automatic Purchase Intent generation for shortages
- Material allocation for in-stock items
- API endpoints for integration and a simple web UI for upload and inspection

This README explains, step-by-step and in detail, how to set up, run, and test the module locally, how the key pieces work, and where to look when something goes wrong.

**Important paths**
- Config & code overview: [CODE_OVERVIEW.md](CODE_OVERVIEW.md)
- Upload service: [app/Services/BomUploadService.php](app/Services/BomUploadService.php)
- Importer: [app/Imports/BomImport.php](app/Imports/BomImport.php)
- Inventory job: [app/Jobs/ProcessBomInventoryJob.php](app/Jobs/ProcessBomInventoryJob.php)
- Web routes: [routes/web.php](routes/web.php)

----

## Requirements

- PHP 8.1+ (8.x compatible with Laravel 10/11)
- Composer
- Node.js & npm (for frontend assets)
- MySQL (or compatible) database
- Optional: Redis (recommended for production queues); the `database` queue driver works for local development
- XAMPP or local web server on Windows (this repo has been tested on Windows)

----

## Quick Setup (complete commands)

1) Clone repository

```bash
git clone <your-repo-url> bom-system
cd bom-system
```

2) Install PHP dependencies

```bash
composer install --no-interaction --prefer-dist
```

3) Install Node dependencies and build assets (optional for UI)

```bash
npm ci
npm run build
```

4) Environment file

Copy the example environment and update values (DB, MAIL, QUEUE_DRIVER, etc.)

```bash
cp .env.example .env
# Edit .env: set DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL
php artisan key:generate
```

5) Database

Create the MySQL database (example name: bom_system). Then run migrations and seeders:

```bash
# create database manually or via mysql client
php artisan migrate --seed
```

Explanation (deep):
- `php artisan migrate` runs all migrations in `database/migrations` and creates schema for projects, `bom_headers`, `bom_line_items`, `inventory`, `purchase_intents`, `purchase_intent_batches`, `material_allocations`, and audit tables. If a migration fails, inspect `storage/logs/laravel.log` and fix the `Schema` or DB credentials in `.env`.
- `--seed` runs database seeders defined in `database/seeders`. Seeders populate sample inventory and basic users/roles (if included). If you want only migrations, omit `--seed`.

----

## Running the Application Locally

There are two common local approaches on Windows:

- Use the built-in artisan server (recommended for local dev):

```bash
php artisan serve --host=127.0.0.1 --port=8000
# Visit http://127.0.0.1:8000/dashboard (requires auth)
```

- Or configure your XAMPP/Apache virtual host to point to the `public/` directory. Ensure `storage` and `bootstrap/cache` are writable by the web server.

----

## Queue Configuration & Running Workers

Why it matters (deep):

The BOM inventory checks and subsequent purchase-intent + allocation logic are designed to run as background jobs so large BOMs don't block a web request. The job `ProcessBomInventoryJob` dispatches and uses `InventoryProcessingService` to process each line.

Local setup options:

- Database driver (works without Redis). Set `QUEUE_CONNECTION=database` in `.env` and run:

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work --tries=3
```

- Redis (recommended): Install Redis and set `QUEUE_CONNECTION=redis` in `.env`. Then start the worker:

```bash
php artisan queue:work redis --tries=3
```

Long-running worker notes:
- Use `supervisor`/systemd in production to restart workers automatically.
- Worker memory leaks: monitor `--memory` and restart periodically in production.

----

## Uploading a BOM (UI) — Step-by-step

1. Log in as a test user (see Roles section below).
2. Open the dashboard at `/dashboard`.
3. Use the upload form to select a BOM file (.xlsx, .xls, .csv) and click "Upload BOM".

What happens after upload (deep):

- `BomUploadService::upload()` creates a `BomHeader` record (immutable after save).
- The raw file is imported using `Maatwebsite\\Excel::import()` and `BomImport` converts rows to `BomLineItem` records.
- After line items are created, the service dispatches `ProcessBomInventoryJob` (queued) with the BOM id.
- The job iterates BOM lines, checks inventory, creates `MaterialAllocation` for in-stock items, and groups `PurchaseIntent` records into a `PurchaseIntentBatch` for shortages.

UI behavior:
- The BOM details page (`/bom/{id}`) shows each line and an `inventory_status` indicator (In Stock / Partial Stock / Out of Stock). If you run the queue worker, statuses will update as the background job completes.

Expected columns in your BOM file (recommended template):

Header row (first row) should contain these columns (case-insensitive):

- `Item Code` or `Part Number`
- `Item Description`
- `UOM` (Unit of Measure)
- `Required Quantity` (numeric)
- `Specification` or `Material Grade`
- `Allocated To` (Department or Role)

This importer also accepts common synonyms such as `Qty`, `Quantity`, `Size`, `Material Grade`, and `Department`.
If your source files use different headings, the import logic can be extended in `app/Services/Bom/BomUploadService.php` and `app/Imports/BomImport.php`.

----

## API Endpoints (examples)

These routes are defined in [routes/web.php](routes/web.php). Example actions / endpoints:

- `GET /dashboard` — Upload form & list of BOMs
- `POST /bom-upload` — Upload BOM (multipart form, file field `file`)
- `GET /bom/{id}` — View BOM details and line statuses
- `GET /purchase-intents` — List purchase intents
- `GET /allocations` — List material allocations

> Note: These are currently web routes with role-based access. The code is ready to be extended into a dedicated JSON API group for mobile or integration clients.

cURL example to upload a BOM (requires authentication cookie or token):

```bash
curl -F "file=@/path/to/BOM.xlsx" -b cookiejar.txt -X POST http://127.0.0.1:8000/bom-upload
```

For API/mobile integration, extend these routes into an authenticated API group and add JSON responses in controllers.

----

## Roles & Testing Credentials

This project uses Spatie role-based authorization. The database seeder now creates the required roles and test users automatically.

Default seeded users:

- Admin: `admin@example.com` / `password`
- Purchase Dept: `purchase@example.com` / `password`
- Engineer: `engineer@example.com` / `password`
- Store Manager: `store@example.com` / `password`

Run the seeders with:

```bash
php artisan db:seed
```

If you want to add additional users, use Tinker and assign a role:

```bash
php artisan tinker
>>> $user = App\Models\User::factory()->create([
...    'email' => 'newuser@example.com',
...    'password' => bcrypt('password'),
...]);
>>> $user->assignRole('Engineer');
```

Route access is restricted by role in the controller and middleware:

- Dashboard, upload, BOM detail: `Admin`, `Engineer`, `Store Manager`, `Purchase Dept`
- Purchase intents: `Admin`, `Purchase Dept`
- Allocations: `Admin`, `Store Manager`

----

## Testing

Run unit and feature tests (if present):

```bash
php artisan test
# or
vendor/bin/phpunit
```

If tests require services (Redis, database), ensure `.env.testing` or testing DB is configured. Use `php artisan migrate --env=testing` to prepare the test DB.

----

## Assignment coverage

This repository now includes the following completed features:

- Dashboard card summaries for BOM / inventory / purchase intent metrics
- BOM upload with Excel/CSV support, header validation, and queued processing
- BOM detail view with pagination on `/bom/{id}`
- Role-based access controls using Spatie roles and controller/middleware guards
- Notification functionality for purchase intent creation and department notification
- Seeders for roles and users, ready for testing with default credentials

## Troubleshooting

- Laravel logs: `storage/logs/laravel.log`. Tail the file to observe runtime errors.
- File permissions: make sure `storage/` and `bootstrap/cache` are writable by the PHP process.
- If imports fail due to Excel parsing, ensure `maatwebsite/excel` is installed and `php_zip` / `ext-xml` extensions are enabled.
- If queued jobs don't run: verify `QUEUE_CONNECTION` in `.env`, run `php artisan queue:work` and check `failed_jobs` table.
- If role-based pages are visible to unauthorized users, ensure seeded roles exist and the current user has been assigned one of the allowed roles.

Common commands for debugging:

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
php artisan queue:work --once
```

----

## Deploy Notes (brief)

- Use a process manager for queue workers (Supervisor/systemd) and use Redis for queues in production.
- Run:

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- Ensure `APP_ENV=production` and `APP_DEBUG=false` in production.

----

## Sample BOM Template (CSV header example)

Create `sample-bom.csv` with the first row headers below. The import expects these headers or similar mapped names in `BomImport`:

```
Item Code,Item Description,UOM,Required Quantity,Specification,Allocated To
ABC-123,Steel Plate,pcs,10,S355,Production
DEF-456,Bolt M8,pcs,100,Grade 8,Assembly
```

Place your sample templates in a `docs/` or `resources/` folder for reference.

----

## Where to look in the code (quick developer map)

- Upload controller: `app/Http/Controllers/BomController.php`
- Upload request validation: `app/Http/Requests/UploadBomRequest.php`
- Upload service: `app/Services/BomUploadService.php`
- Import mapping: `app/Imports/BomImport.php`
- Job: `app/Jobs/ProcessBomInventoryJob.php`
- Inventory logic: `app/Services/InventoryProcessingService.php`
- Models: `app/Models/BomHeader.php`, `app/Models/BomLineItem.php`, `app/Models/Inventory.php`, `app/Models/PurchaseIntent.php`, `app/Models/MaterialAllocation.php`

----

## Next Steps / Recommendations

1. Verify database seeders create sample inventory and test users. If not, I can add seeders to populate test data.
2. Add a `docs/sample-bom.xlsx` template matching the expected column layout.
3. Add an API JSON controller group to support mobile clients.
4. Add automated tests around upload + inventory processing flow.

----

If you want, I can now:

- Run the test suite locally and report failures.
- Add a `docs/sample-bom.xlsx` and update the `BomImport` mapping to be more flexible.
- Create or fix seeders to ensure roles and inventory exist for testing.

Tell me which of the above you want me to do next.

