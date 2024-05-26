<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTheme extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'theme', 'scale', 'darkTheme', 'menuMode', 'ripple', 'activeMenuItem', 'inputStyle'];

    protected $casts = [
        'darkTheme' => 'bool',
        'ripple' => 'bool',
        'activeMenuItem' => 'bool'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
