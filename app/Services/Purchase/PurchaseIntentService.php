<?php

namespace App\Services\Purchase;

use App\Models\ActivityLog;
use App\Models\PurchaseIntent;
use App\Models\User;
use App\Notifications\PurchaseIntentNotification;

class PurchaseIntentService
{
    /**
     * Create a new class instance.
     */
    public function create($batchId, $item, $availableQty)
    {
        $intent = PurchaseIntent::create([
            'batch_id' => $batchId,
            'bom_line_item_id' => $item->id,
            'item_code' => $item->part_number,
            'description' => $item->description,
            'required_qty' => $item->required_qty,
            'available_qty' => $availableQty,
            'shortfall_qty' => $item->required_qty - $availableQty,
            'priority' => 'Medium',
            'status' => 'Pending',
        ]);

        ActivityLog::create([
            'action' => 'purchase_intent.created',
            'description' => sprintf(
                'Created purchase intent for item %s with shortfall %s',
                $intent->item_code,
                $intent->shortfall_qty
            ),
            'performed_by' => 'System - Auto',
        ]);

        $purchaseUsers = User::role('Purchase Dept')->get();

        foreach ($purchaseUsers as $user) {
            $user->notify(new PurchaseIntentNotification($intent));
        }
    }
}
