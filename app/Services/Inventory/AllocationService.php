<?php

namespace App\Services\Inventory;

use App\Models\MaterialAllocation;

class AllocationService
{
    /**
     * Create a new class instance.
     */
    public function allocate($item, $inventory, $qty)
    {
        $inventory->decrement('available_qty', $qty);

        return MaterialAllocation::create([
            'bom_line_item_id' => $item->id,
            'item_code' => $item->part_number,
            'description' => $item->description,
            'allocated_qty' => $qty,
            'allocated_to' => $item->allocated_to,
            'allocated_at' => now(),
        ]);
    }
}
