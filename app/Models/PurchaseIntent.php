<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseIntent extends Model
{
    protected $fillable = [
        'batch_id',
        'item_code',
        'requested_qty',
        'available_qty',
        'shortfall_qty',
        'priority',
    ];  
}
