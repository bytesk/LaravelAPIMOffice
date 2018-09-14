<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{

    use Notifiable;


    protected $fillable = [
        'fullName', 'email', 'password', 'phone',
        'profile_picture_url', 'role', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token', 'role', 'status'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
