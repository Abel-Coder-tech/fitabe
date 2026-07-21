<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = ['email', 'actif'];

    protected function casts(): array
    {
        return ['actif' => 'boolean'];
    }
}
