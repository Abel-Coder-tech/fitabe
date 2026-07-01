<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametres extends Model
{
    protected $fillable = [
        'cle',
        'valeur',
    ];

    protected function casts(): array
    {
        return [
            'valeur' => 'string',
        ];
    }
}
