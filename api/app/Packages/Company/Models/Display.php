<?php

namespace App\Packages\Company\Models;

use Illuminate\Database\Eloquent\Model;
use App\Packages\Company\Models\DisplaySource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Display extends Model
{
    use HasFactory;

    // Especifique o nome da tabela, se for diferente do padrão (opcional)
    protected $table = 'displays';

    // Defina os campos que podem ser preenchidos em massa (opcional)
    protected $fillable = ['company_id','name', 'visible'];

    // Defina os campos que devem ser ocultos ao serializar o modelo para JSON (opcional)
    protected $hidden = [];

    // Defina os campos que devem ser convertidos para tipos nativos (opcional)
    protected $casts = [
        'visible' => 'boolean',
    ];

    // Relacionamento belongsTo com Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relacionamento hasMany com DisplaySource
    public function displaySources()
    {
        return $this->hasMany(DisplaySource::class);
    }

}