<?php

namespace App\Services\Inventory;

use App\Models\Inventory;

class InventoryCheckService
{
    /**
     * Create a new class instance.
     */
    public function check($item)
    {
        return Inventory::where('item_code', $item->part_number)
            ->orWhere('item_code', $item->item_code)
            ->first();
    }
}
