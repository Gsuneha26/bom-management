<?php

namespace App\Jobs;

use App\Models\BomHeader;
use App\Models\Inventory;
use App\Models\MaterialAllocation;
use App\Models\PurchaseIntent;
use App\Models\PurchaseIntentBatch;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\Processing\BomProcessingService;

class ProcessBomInventoryJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $bomId;

    public function __construct($bomId)
    {
        $this->bomId = $bomId;
    }

    public function handle(BomProcessingService $service)
    {
        $bom = BomHeader::with('items')->findOrFail($this->bomId);

        $bom->update([
            'status' => 'processing'
        ]);

        try {

            $service->process($bom);

            // success
            $bom->update([
                'status' => 'completed'
            ]);

        } catch (\Exception $e) {

            // failed
            $bom->update([
                'status' => 'failed'
            ]);

            throw $e;
        }
    }
}