<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseIntentBatch extends Model
{
    protected $fillable = [
        'bom_header_id',
        'batch_reference',
        'total_items',
        'total_shortfall_qty',
        'created_by',
        'status',
    ];
}
