<?php

namespace App\Packages\Company\Resources;

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
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'client' => $this->client,
            'cnpj' => $this->cnpj,
            'users' => UserResource::collection($this->users),
        ];
    }
}