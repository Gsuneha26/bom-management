<?php

namespace App\Services\Processing;

use App\Models\ActivityLog;
use App\Services\Inventory\InventoryCheckService;
use App\Services\Inventory\AllocationService;
use App\Services\Purchase\PurchaseIntentService;
use App\Models\PurchaseIntentBatch;

class BomProcessingService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private InventoryCheckService $inventoryService,
        private AllocationService $allocationService,
        private PurchaseIntentService $purchaseService,
    )
    {
        //
    }

    public function process($bom)
    {
        ActivityLog::create([
            'action' => 'bom.processing.started',
            'description' => sprintf('Processing BOM %s', $bom->bom_reference),
            'performed_by' => 'System - Auto',
        ]);

        $batch = PurchaseIntentBatch::create([
            'bom_header_id' => $bom->id,
            'batch_reference' => 'PIB-' . time(),
        ]);

        foreach ($bom->items as $item) {
            $inventory = $this->inventoryService->check($item);

            if (!$inventory) {
                $item->update(['inventory_status' => 'OUT OF STOCK']);
                $this->purchaseService->create($batch->id, $item, 0);
                continue;
            }

            if ($inventory->available_qty >= $item->required_qty) {
                $item->update(['inventory_status' => 'IN STOCK']);
                $this->allocationService->allocate(
                    $item,
                    $inventory,
                    $item->required_qty
                );
            } else {
                $item->update(['inventory_status' => 'PARTIAL STOCK']);

                $available = $inventory->available_qty;

                if ($available > 0) {
                    $this->allocationService->allocate(
                        $item,
                        $inventory,
                        $available
                    );
                }

                $this->purchaseService->create(
                    $batch->id,
                    $item,
                    $available
                );
            }
        }

        ActivityLog::create([
            'action' => 'bom.processing.completed',
            'description' => sprintf('Completed processing BOM %s', $bom->bom_reference),
            'performed_by' => 'System - Auto',
        ]);
    }
}
