<?php

namespace App\Services\Inventory;

use App\Models\ActivityLog;
use App\Models\MaterialAllocation;

class AllocationService
{
    /**
     * Create a new class instance.
     */
    public function allocate($item, $inventory, $qty)
    {
        $inventory->decrement('available_qty', $qty);

        $allocation = MaterialAllocation::create([
            'bom_line_item_id' => $item->id,
            'item_code' => $item->part_number,
            'description' => $item->description,
            'allocated_qty' => $qty,
            'allocated_to' => $item->allocated_to,
            'allocated_at' => now(),
        ]);

        ActivityLog::create([
            'action' => 'material.allocation',
            'description' => sprintf(
                'Allocated %s %s to %s for BOM item %s',
                $allocation->allocated_qty,
                $item->unit,
                $allocation->allocated_to,
                $item->part_number ?: $item->item_code
            ),
            'performed_by' => 'System - Auto',
        ]);

        return $allocation;
    }
}
