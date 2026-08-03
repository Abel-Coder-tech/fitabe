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
        return (int) static::where('categorie', $categorie)->value('places') ?: 1000;
    }
}
