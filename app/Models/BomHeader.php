<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomHeader extends Model
{
    protected $fillable = [
        'project_id',
        'bom_reference',
        'file_name',
        'version',
        'uploaded_by',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(BomLineItem::class);
    }
}
