<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomLineItem extends Model
{
    protected $fillable = [
        'bom_header_id',
        'item_code',
        'description',
        'part_number',
        'size_of_material',
        'required_qty',
        'unit',
        'specifications',
        'allocated_to',
        'inventory_status',
    ];
}
