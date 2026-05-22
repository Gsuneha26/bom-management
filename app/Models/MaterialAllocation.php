<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialAllocation extends Model
{
    protected $fillable = [
        'bom_line_item_id',
        'item_code',
        'description',
        'allocated_qty',
        'allocated_to',
        'allocated_by',
        'allocated_at',
    ];
}
