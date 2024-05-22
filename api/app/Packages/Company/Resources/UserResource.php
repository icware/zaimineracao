<?php

namespace App\Packages\Company\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($this->id !== null, $this->id),
            'name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'role' => $this->pivot->role,
        ];
    }
}