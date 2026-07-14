<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
        'name' => $this->name,
        'website' => $this->website,
        'industry' => $this->industry,
        'phone' => $this->phone,
        'address' => $this->address,
        'user_id' => $this->user_id,
        // Nesting data cleanly using your existing relationships
        'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
