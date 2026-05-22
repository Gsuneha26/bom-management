<?php

namespace App\Services;
use App\Models\Inventory;
use App\Models\BomHeader;
use App\Models\PurchaseIntent;
use App\Models\PurchaseIntentBatch;
use App\Models\MaterialAllocation;
use App\Models\ActivityLog;

class InventoryProcessingService
{
    public function process($bomHeaderId)
    {
        $bom = BomHeader::with('items')
            ->findOrFail($bomHeaderId);

        $batch = PurchaseIntentBatch::create([
            'bom_header_id' => $bom->id,
            'batch_reference' => 'PIB-' . time(),
        ]);

        foreach ($bom->items as $item) {

            $partNumber = trim((string) $item->part_number);
            $itemCode = trim((string) $item->item_code);
            $inventory = null;

            if ($partNumber !== '' || $itemCode !== '') {
                $inventory = Inventory::where(function ($query) use ($partNumber, $itemCode) {
                    if ($partNumber !== '') {
                        $query->where('item_code', $partNumber);
                    }

                    if ($itemCode !== '') {
                        $query->orWhere('item_code', $itemCode);
                    }
                })->first();
            }

            if (!$inventory) {

                $item->update([
                    'inventory_status' => 'OUT OF STOCK'
                ]);

                PurchaseIntent::create([
                    'batch_id' => $batch->id,
                    'bom_line_item_id' => $item->id,
                    'item_code' => $item->part_number,
                    'description' => $item->description,
                    'required_qty' => $item->required_qty,
                    'available_qty' => 0,
                    'shortfall_qty' => $item->required_qty,
                ]);

                continue;
            }
            if ($inventory->available_qty >= $item->required_qty) {

                $item->update([
                    'inventory_status' => 'IN STOCK'
                ]);

                $inventory->decrement(
                    'available_qty',
                    $item->required_qty
                );

                MaterialAllocation::create([
                    'bom_line_item_id' => $item->id,
                    'item_code' => $item->part_number,
                    'description' => $item->description,
                    'allocated_qty' => $item->required_qty,
                    'allocated_to' => $item->allocated_to,
                    'allocated_at' => now(),
                ]);

            } else {

                $item->update([
                    'inventory_status' => 'PARTIAL STOCK'
                ]);

                $available = $inventory->available_qty;

                if ($available > 0) {

                    MaterialAllocation::create([
                        'bom_line_item_id' => $item->id,
                        'item_code' => $item->part_number,
                        'description' => $item->description,
                        'allocated_qty' => $available,
                        'allocated_to' => $item->allocated_to,
                        'allocated_at' => now(),
                    ]);

                    $inventory->update([
                        'available_qty' => 0
                    ]);
                }

                PurchaseIntent::create([
                    'batch_id' => $batch->id,
                    'bom_line_item_id' => $item->id,
                    'item_code' => $item->part_number,
                    'description' => $item->description,
                    'required_qty' => $item->required_qty,
                    'available_qty' => $available,
                    'shortfall_qty' => $item->required_qty - $available,
                ]);
            }
        }

        ActivityLog::create([
            'action' => 'Inventory Processing',
            'description' => 'Inventory checked for BOM #' . $bom->id,
        ]);
    }
}