<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Packages\Company\Models\Company;
use Illuminate\Notifications\Notifiable;
use App\Packages\Company\Models\Associate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'active',
        'company',
        'super',
        'code',
        'birth',
        'reset_password_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'reset_password_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth' => 'date',
        'password' => 'hashed',
        'active' => 'boolean',
        'company' => 'boolean',
        'super' => 'boolean',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function associates()
    {
        return $this->hasMany(Associate::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'associates')->withPivot('role', 'enabled');

    }

}
