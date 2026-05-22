<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bom_reference' => $this->bom_reference,
            'file_name' => $this->file_name,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}

