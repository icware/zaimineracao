<?php

namespace App\Packages\Company\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Packages\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Associate extends Model
{
    use HasFactory;

    // Especifique o nome da tabela, se for diferente do padrão (opcional)
    protected $table = 'associates';

    // Defina os campos que podem ser preenchidos em massa (opcional)
    protected $fillable = ['company_id', 'user_id', 'role', 'enabled'];

    // Defina os campos que devem ser ocultos ao serializar o modelo para JSON (opcional)
    protected $hidden = [];

    // Defina os campos que devem ser convertidos para tipos nativos (opcional)
    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
