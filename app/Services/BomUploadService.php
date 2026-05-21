<?php

namespace App\Services;

use App\Models\BomHeader;
use App\Models\BomLineItem;
use App\Imports\BomImport;
use App\Imports\BomLineImport;

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
                if (empty($row[0])) {
                    continue;
                }
                
                BomLineItem::create([

                    'bom_header_id' => $bom->id,

                    'item_code' => $row[0] ?? null,

                    'description' => $row[1] ?? null,

                    'unit' => $row[6] ?? null,

                    'required_qty' => is_numeric($row[5])
                        ? $row[5]
                        : 0,

                    'specifications' => $row[3] ?? null,

                    'inventory_status' => $row[9] ?? null,
                ]);
            }

            return $bom;
        });
    }
}
