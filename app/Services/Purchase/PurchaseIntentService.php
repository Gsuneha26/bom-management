<?php

namespace App\Services\Purchase;

use App\Models\PurchaseIntent;

class PurchaseIntentService
{
    /**
     * Create a new class instance.
     */
    public function create($batchId, $item, $availableQty)
    {
        return PurchaseIntent::create([
            'batch_id' => $batchId,
            'bom_line_item_id' => $item->id,
            'item_code' => $item->part_number,
            'description' => $item->description,
            'required_qty' => $item->required_qty,
            'available_qty' => $availableQty,
            'shortfall_qty' => $item->required_qty - $availableQty,
        ]);
    }
}
