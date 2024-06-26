<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'system_logs';

    protected $fillable = ['message', 'level', 'context', 'action'];

    protected $casts = [
        'context' => 'array',
    ];
}
