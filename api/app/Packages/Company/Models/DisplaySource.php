<?php

namespace App\Packages\Company\Models;

use Illuminate\Database\Eloquent\Model;
use App\Packages\Company\Models\Display;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisplaySource extends Model
{
    use HasFactory;

    // Especifique o nome da tabela, se for diferente do padrão (opcional)
    protected $table = 'display_sources';

    // Defina os campos que podem ser preenchidos em massa (opcional)
    protected $fillable = [
        'display_id',
        'service_code',
        'source_id', 
        'source_key', 
        'source_format',
        'visible', 
        'settings'];

    // Defina os campos que devem ser ocultos ao serializar o modelo para JSON (opcional)
    protected $hidden = [];

    // Defina os campos que devem ser convertidos para tipos nativos (opcional)
    protected $casts = [
        'visible' => 'boolean',
        'settings' => 'array',
    ];

    // Relacionamento belongsTo com Display
    public function display()
    {
        return $this->belongsTo(Display::class);
    }
   
}