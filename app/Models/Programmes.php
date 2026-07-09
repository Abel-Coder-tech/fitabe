<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programmes extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'icone',
        'couleur_bordure',
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

    public function dates(): HasMany
    {
        return $this->hasMany(ProgrammeDate::class, 'programme_id')->orderBy('ordre');
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
