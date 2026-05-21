<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialAllocation extends Model
{
    protected $fillable = [
        'bom_line_item_id',
        'item_code',
        'allocated_qty',
        'allocation_to',
        'allocated_by',
    ];
}
