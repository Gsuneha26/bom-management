<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseIntent extends Model
{
    protected $fillable = [
        'batch_id',
        'bom_line_item_id',
        'item_code',
        'description',
        'required_qty',
        'available_qty',
        'shortfall_qty',
        'priority',
        'status',
    ];  
}
