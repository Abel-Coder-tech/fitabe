<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacesCategorie extends Model
{
    protected $fillable = [
        'categorie',
        'places',
    ];

    protected function casts(): array
    {
        return [
            'places' => 'integer',
        ];
    }

    // Nombre de places configuré pour une catégorie (défaut : illimité)
    public static function pour(string $categorie): int
    {
        return (int) static::allKeyed()->get($categorie, 1000);
    }

    // Retourne toutes les places groupées par catégorie (1 seule requête, mémoïsée)
    public static function allKeyed(): \Illuminate\Support\Collection
    {
        return once(fn () => static::pluck('places', 'categorie'));
    }
}
