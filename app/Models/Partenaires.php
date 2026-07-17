<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Partenaires extends Model
{
    // Champs assignables en masse
    protected $fillable = [
        'nom',
        'logo',
        'site_web',
        'description',
        'ordre_affichage',
    ];

    protected function casts(): array
    {
        return [
            'ordre_affichage' => 'integer',
        ];
    }

    // Accesseur : URL complète du logo
    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : '';
    }

    // Scope : trié par ordre d'affichage
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('ordre_affichage');
    }
}
