<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user'; // tabulka 'user'

    // Vypnutie 'updated_at'
    const UPDATED_AT = null;

    // Zachovanie 'created_at'
    public $timestamps = true;


    protected $fillable = [
        'name',
        'email',
        'password',
        'contact',
        'verified',
        'isAdmin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'isAdmin' => 'boolean',
    ];
}
