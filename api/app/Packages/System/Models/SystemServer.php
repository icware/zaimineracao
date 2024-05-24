<?php

namespace App\Packages\System\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemServer extends Model
{
    use HasFactory;

    protected $table = 'servers';

    protected $fillable = ['name', 'code', 'type', 'address', 'enabled'];

    protected $hidden = [];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
