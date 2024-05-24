<?php

namespace App\Packages\Company\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Packages\Company\Models\Display;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    // Especifique o nome da tabela, se for diferente do padrão (opcional)
    protected $table = 'companies';

    // Defina os campos que podem ser preenchidos em massa (opcional)
    protected $fillable = [
        'name', // Company name
        'code', // Unique company code
        'cnpj', // Unique CNPJ of the company
        'responsible_cpf', // CPF of the responsible person
        'company_name', // Official company name (Razão Social)
        'address_type', // Type of address (Tipo Logradouro)
        'address', // Street address (Logradouro)
        'number', // Address number (Número)
        'complement', // Address complement (Complemento)
        'neighborhood', // Neighborhood (Bairro)
        'postal_code', // Postal code (CEP)
        'state', // State (Estado)
        'country', // Country (País)
        'phone', // Contact phone number (Telefone)
        'email', // Contact email (Email)
        'trading_name', // Trading name (Nome Fantasia)
        'registration_status', // Registration status (Situação Cadastral)
        'client', // Client name
        'status' // Company status (active/inactive)
    ];

    // Defina os campos que devem ser ocultos ao serializar o modelo para JSON (opcional)
    protected $hidden = [];

    // Defina os campos que devem ser convertidos para tipos nativos (opcional)
    protected $casts = [
        'status' => 'boolean',
    ];

    public function associates()
    {
        return $this->hasMany(Associate::class);
    }

    // Relacionamento belongsToMany com User
    public function users()
    {
        return $this->belongsToMany(User::class, 'associates')->withPivot('role', 'enabled');
    }

    // Relacionamento hasMany com Display
    public function displays()
    {
        return $this->hasMany(Display::class);
    }
}
