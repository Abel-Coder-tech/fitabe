<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Resultat extends Model
{
    // Champs assignables en masse
    protected $fillable = [
        'annee_edition', 'categorie', 'prix',
        'candidat_nom', 'candidat_photo', 'nombre_votes',
        'note_jury', 'score_public', 'score_final',
    ];

    // Accesseur : URL complète de la photo du candidat
    public function getCandidatPhotoUrlAttribute(): string
    {
        return $this->candidat_photo ? asset('storage/' . $this->candidat_photo) : '';
    }

    // Accesseur : libellé du prix (1er, 2ème, 3ème...)
    public function getPrixLabelAttribute(): string
    {
        return match ($this->prix) {
            1 => '1er Prix',
            2 => '2ème Prix',
            3 => '3ème Prix',
            default => $this->prix . 'ème Prix',
        };
    }

    // Scope : filtre par année d'édition
    public function scopeByEdition(Builder $query, string $annee): Builder
    {
        return $query->where('annee_edition', $annee);
    }

    // Scope : filtre par catégorie
    public function scopeByCategorie(Builder $query, string $categorie): Builder
    {
        return $query->where('categorie', $categorie);
    }

    // Scope : top 3 des prix
    public function scopeTop3(Builder $query): Builder
    {
        return $query->whereIn('prix', [1, 2, 3])->orderBy('prix');
    }

    // Calcule le score final (jury 60% + public 40%)
    public function recalculerScoreFinal(): void
    {
        if ($this->note_jury !== null && $this->score_public !== null) {
            $this->score_final = round(($this->note_jury * 0.6) + ($this->score_public * 0.4), 2);
        }
    }
}
