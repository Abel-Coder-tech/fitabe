<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    // Champs assignables en masse
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Attributs masqués dans les tableaux/sérialisation
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts des attributs
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
