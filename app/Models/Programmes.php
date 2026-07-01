<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Programmes extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'date_programme',
        'lieu',
        'categorie',
        'ordre',
        'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'date_programme' => 'datetime',
            'est_actif' => 'boolean',
            'ordre' => 'integer',
        ];
    }

    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('ordre');
    }
}
