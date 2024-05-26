<?php

namespace App\Packages\Service\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Especifique o nome da tabela, se for diferente do padrão (opcional)
    protected $table = 'services';

    // Defina os campos que podem ser preenchidos em massa (opcional)
    protected $fillable = ['name', 'code', 'enabled', 'server', 'type', 'settings',];

    // Defina os campos que devem ser ocultos ao serializar o modelo para JSON (opcional)
    protected $hidden = [];

    // Defina os campos que devem ser convertidos para tipos nativos (opcional)
    protected $casts = [
        'enabled' => 'bool',
        'settings' => 'array',
    ];
}