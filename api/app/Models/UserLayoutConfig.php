<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLayoutConfig extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'theme', 'scale', 'dark_mode', 'menu_mode'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
