<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'      => $this->uuid,
            'name'      => $this->name,
            'type'      => $this->type,
            'color'     => $this->color,
            'icon'      => $this->icon,
            'is_system' => (bool) $this->is_system, 
        ];
    }
}
