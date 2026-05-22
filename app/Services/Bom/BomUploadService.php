<?php

namespace App\Services\Bom;

use App\Models\ActivityLog;
use App\Models\BomHeader;
use App\Models\BomLineItem;
use App\Imports\BomImport;
use App\Jobs\ProcessBomInventoryJob;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BomUploadService
{
    /**
     * Create a new class instance.
     */
    public function upload($request)
    {
        return DB::transaction(function () use ($request) {
            $projectId = 1;

            $bom = BomHeader::create([
                'project_id' => $projectId,
                'bom_reference' => 'BOM-' . time(),
                'file_name' => $request->file('file')->getClientOriginalName(),
                'version' => $this->nextVersion($projectId),
                'uploaded_by' => optional($request->user())->id,
                'status' => 'pending',
            ]);

            $import = new BomImport();
            Excel::import($import, $request->file('file'));

            $rows = $import->rows;

            if ($rows->isEmpty()) {
                throw new \Exception('BOM file is empty or missing a valid header row.');
            }

            $this->validateHeaders($rows);

            foreach ($rows as $row) {
                if ($this->isEmptyRow($row)) {
                    continue;
                }

                BomLineItem::create([
                    'bom_header_id' => $bom->id,
                    'item_code' => $this->resolve($row, ['item_code', 'part_number', 'itemcode', 'partnumber']),
                    'description' => $this->resolve($row, ['item_description', 'description', 'desc']),
                    'part_number' => $this->resolve($row, ['part_number', 'item_code', 'itemcode', 'partnumber']),
                    'specifications' => $this->resolve($row, ['specification', 'material_grade', 'specifications']),
                    'size_of_material' => $this->resolve($row, ['size_of_material', 'size', 'material_size']),
                    'required_qty' => $this->toFloat($this->resolve($row, ['required_quantity', 'quantity', 'qty', 'required_qty'])),
                    'unit' => $this->resolve($row, ['uom', 'unit']),
                    'allocated_to' => $this->resolve($row, ['allocated_to', 'department', 'assigned_to']),
                    'inventory_status' => null,
                ]);
            }

            ActivityLog::create([
                'action' => 'bom.upload',
                'description' => sprintf(
                    'Uploaded BOM %s (%s) by %s',
                    $bom->bom_reference,
                    $bom->file_name,
                    optional($request->user())->email ?? 'System'
                ),
                'performed_by' => optional($request->user())->email ?? 'System',
            ]);

            ProcessBomInventoryJob::dispatch($bom->id);

            return $bom;
        });
    }

    protected function nextVersion(int $projectId): int
    {
        $maxVersion = BomHeader::where('project_id', $projectId)->max('version');
        return $maxVersion ? $maxVersion + 1 : 1;
    }

    protected function validateHeaders($rows): void
    {
        $firstRow = $rows->first();
        $headers = $firstRow->keys()->map(fn ($key) => strtolower(trim((string)$key)));

        $missing = [];

        if (! $headers->contains(fn ($header) => in_array($header, ['item_code', 'part_number', 'itemcode', 'partnumber']))) {
            $missing[] = 'Item Code / Part Number';
        }

        if (! $headers->contains(fn ($header) => in_array($header, ['item_description', 'description', 'desc']))) {
            $missing[] = 'Item Description';
        }

        if (! $headers->contains(fn ($header) => in_array($header, ['uom', 'unit']))) {
            $missing[] = 'UOM';
        }

        if (! $headers->contains(fn ($header) => in_array($header, ['required_quantity', 'quantity', 'qty', 'required_qty']))) {
            $missing[] = 'Required Quantity';
        }

        if (! empty($missing)) {
            throw new \Exception('Missing required columns: ' . implode(', ', $missing));
        }
    }

    protected function resolve($row, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && $row[$key] !== null && $row[$key] !== '') {
                return $row[$key];
            }
        }

        return $default;
    }

    protected function toFloat($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $normalized = str_replace([',', ' '], ['', ''], $value);
            return is_numeric($normalized) ? (float) $normalized : 0;
        }

        return 0;
    }

    protected function isEmptyRow($row): bool
    {
        return collect($row)->filter(fn ($value) => $value !== null && $value !== '')->isEmpty();
    }
}
