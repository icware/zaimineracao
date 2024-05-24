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
            'id' => $this->id, // ID of the company
            'name' => $this->name, // Company name
            'code' => $this->code, // Unique company code
            'client' => $this->client, // Client name
            'cnpj' => $this->cnpj, // Unique CNPJ of the company
            'responsible_cpf' => $this->responsible_cpf, // CPF of the responsible person
            'company_name' => $this->company_name, // Official company name (Razão Social)
            'address_type' => $this->address_type, // Type of address (Tipo Logradouro)
            'address' => $this->address, // Street address (Logradouro)
            'number' => $this->number, // Address number (Número)
            'complement' => $this->complement, // Address complement (Complemento)
            'neighborhood' => $this->neighborhood, // Neighborhood (Bairro)
            'postal_code' => $this->postal_code, // Postal code (CEP)
            'state' => $this->state, // State (Estado)
            'country' => $this->country, // Country (País)
            'phone' => $this->phone, // Contact phone number (Telefone)
            'email' => $this->email, // Contact email (Email)
            'trading_name' => $this->trading_name, // Trading name (Nome Fantasia)
            'registration_status' => $this->registration_status, // Registration status (Situação Cadastral)
            'status' => $this->status, // Company status (active/inactive)
            'users' => UserResource::collection($this->users), // Collection of associated users
        ];
    }
}
