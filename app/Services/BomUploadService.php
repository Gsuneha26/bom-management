<?php

namespace App\Services;

use App\Models\BomHeader;
use App\Models\BomLineItem;
use App\Imports\BomImport;
use App\Imports\BomLineImport;
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

            $bom = BomHeader::create([
                'project_id' => 1,
                'bom_reference' => 'BOM-' . time(),
                'file_name' => $request->file('file')
                    ->getClientOriginalName(),
            ]);

            $import = new BomImport();

            Excel::import(
                $import,
                $request->file('file')
            );

            $rows = $import->rows;

            foreach ($rows as $row) {

                // Skip empty rows
                if (
                    empty($row[0]) ||
                    empty($row[1]) ||
                    !is_numeric(str_replace('.', '', $row[0]))
                ) {
                    continue;
                }
                BomLineItem::create([

                    'bom_header_id' => $bom->id,

                    'item_code' => $row[0] ?? null,

                    'description' => $row[1] ?? null,

                    'part_number' => $row[2] ?? null,

                    'specifications' => $row[3] ?? null,

                    'size_of_material' => $row[4] ?? null,

                    'required_qty' => is_numeric($row[5])
                        ? $row[5]
                        : 0,

                    'unit' => $row[6] ?? null,

                    'allocated_to' => $row[7] ?? null,

                    'inventory_status' => null,


                ]);
            }

            ProcessBomInventoryJob::dispatch($bom->id);
            return $bom;
        });
    }
}
