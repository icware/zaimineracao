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
    protected $fillable = ['name','code', 'cnpj', 'client','status'];

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
     public function displays()     {
         return $this->hasMany(Display::class);
     }
}