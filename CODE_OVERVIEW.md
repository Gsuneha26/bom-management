# BOM System Code Overview

This document describes the current BOM upload flow in the application, the main files involved, and how they work together.

## 1. Entry Point: `routes/web.php`
- Defines the web routes for the application.
- `GET /` returns the welcome page.
- `GET /dashboard` returns the dashboard view and is protected by `auth` and `verified` middleware.
- `POST /bom-upload` maps to `BomController@store` and is also protected by `auth` because it is inside the authenticated group.
- `GET /bom/{id}` maps to `BomController@show` to display a single BOM.

## 2. Controller: `app/Http/Controllers/BomController.php`
- `index()` loads BOM headers and passes them to `dashboard.blade.php`.
  - Uses `BomHeader::latest()->withCount('items')->get()`.
- `store(UploadBomRequest $request, BomUploadService $service)` handles the upload request.
  - Uses a form request to validate the upload.
  - Delegates the actual import work to `BomUploadService::upload()`.
  - Returns a JSON response on success.
- `show($id)` loads a single BOM and its line items for the BOM detail page.

## 3. Validation: `app/Http/Requests/UploadBomRequest.php`
- Extends `FormRequest` and controls authorization and validation.
- `authorize()` currently returns `true`, allowing upload requests through.
- `rules()` requires:
  - `file` as a required upload with MIME types `xlsx`, `xls`, or `csv`.
- Note: `project_id` is currently commented out and not validated.

## 4. View: `resources/views/dashboard.blade.php`
- Displays the upload form and the list of previously uploaded BOMs.
- The form uses `POST` to `route('bom.upload')`.
- Includes `@csrf` and `enctype="multipart/form-data"`.
- Only includes a `file` input today.
- Shows an HTML table of uploaded BOM headers with a link to view each BOM.

## 5. Service: `app/Services/BomUploadService.php`
- Handles the actual BOM upload and import process.
- `upload($request)` runs inside a database transaction.
- Creates a new `BomHeader` record using:
  - `project_id` hardcoded to `1`
  - `bom_reference` set to `BOM-{timestamp}`
  - `file_name` from the uploaded file's original name
- Imports the uploaded file using `Maatwebsite\\Excel::import()` and the custom `BomImport` class.
- Iterates over imported rows and creates `BomLineItem` records for each BOM row.
  - Skips rows where the first two columns are empty or the first column is not numeric.
- Dispatches `ProcessBomInventoryJob` with the newly created BOM ID.

## 6. Import Class: `app/Imports/BomImport.php`
- Implements `ToCollection` and `WithCalculatedFormulas` from Laravel Excel.
- Stores imported rows in a public `$rows` property.
- The service reads `$import->rows` after import completes.

## 7. Job: `app/Jobs/ProcessBomInventoryJob.php`
- Queues inventory processing after a BOM is uploaded.
- Accepts `$bomId` in the constructor.
- The `handle()` method resolves `InventoryProcessingService` and calls `process($this->bomId)`.
- Fixed bug: this file previously referenced an undefined `$this->bomHeaderId`; it now uses the correct property.

## 8. Inventory Processing: `app/Services/InventoryProcessingService.php`
- Loads the BOM header with its `items` relationship.
- Creates a `PurchaseIntentBatch` for the BOM.
- For each `BomLineItem`:
  - Tries to find an `Inventory` row matching `item_code = part_number`.
  - If no inventory exists:
    - Marks the item as `OUT OF STOCK`.
    - Creates a `PurchaseIntent` for the full required quantity.
  - If inventory is enough:
    - Marks item `IN STOCK`.
    - Decrements inventory by the required quantity.
    - Creates a `MaterialAllocation` record.
  - If inventory is partial:
    - Marks item `PARTIAL STOCK`.
    - Allocates available quantity.
    - Creates a `PurchaseIntent` for the shortfall quantity.
- Writes an `ActivityLog` entry when processing is complete.

## 9. Models
### `app/Models/BomHeader.php`
- Fillable: `project_id`, `bom_reference`, `file_name`, `version`, `uploaded_by`, `status`.
- Relationship: `items()` returns `hasMany(BomLineItem::class)`.

### `app/Models/BomLineItem.php`
- Fillable fields include line item details and inventory status.
- No extra relationships are defined in this file.

## 10. Current Known Issues and Recommended Updates
- `app/Jobs/ProcessBomInventoryJob.php` was fixed to pass the correct property to `InventoryProcessingService::process()`.
- `app/Services/BomUploadService.php` hardcodes `project_id` to `1`; this should be replaced with real project selection if multiple projects exist.
- The upload form currently only includes `file`; if `project_id` is required later, the form and validation should be updated together.
- The service currently imports every row from the file without column headers handling; if the file has headers, the import logic should be improved.

## 11. How the upload flow works
1. User opens `/dashboard` and submits the upload form.
2. The form sends `POST /bom-upload` with a file upload.
3. `UploadBomRequest` validates the file.
4. `BomController@store()` calls `BomUploadService::upload()`.
5. The service creates a BOM header, imports file rows, and creates BOM line items.
6. `ProcessBomInventoryJob` is queued to check inventory and create purchase intents.
7. A JSON success response is returned from the controller.

---

This file is meant to be a code-level overview of the main BOM upload flow in the application. If you want, I can also add a second section documenting `PurchaseIntent` and inventory model relationships in detail.