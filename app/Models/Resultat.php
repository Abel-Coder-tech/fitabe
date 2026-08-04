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
        'note_jury', 'note_technique', 'note_originalite', 'note_presence', 'note_authenticite',
        'score_public', 'score_final', 'publie',
    ];

    protected function casts(): array
    {
        return [
            'publie' => 'boolean',
        ];
    }

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

    // Formate un score sans décimales inutiles (90.00 -> 90, 80.50 -> 80.5)
    public static function formaterScore(mixed $valeur): string
    {
        if ($valeur === null || $valeur === '') {
            return '-';
        }
        return rtrim(rtrim(number_format((float) $valeur, 2, '.', ''), '0'), '.');
    }

    // Score public affiché (fallback score final -> score public -> '-')
    public function getScorePublicAffichageAttribute(): string
    {
        return static::formaterScore($this->score_public);
    }

    // Score final affiché (fallback : score public si aucun score final)
    public function getScoreFinalAffichageAttribute(): string
    {
        return static::formaterScore($this->score_final ?? $this->score_public);
    }

    // Note jury affichée
    public function getNoteJuryAffichageAttribute(): string
    {
        return static::formaterScore($this->note_jury);
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

    // Calcule le score final selon le règlement : ovations /15 + technique /30 + originalité /25 + présence /20 + authenticité /10 = 100
    public function recalculerScoreFinal(): void
    {
        if (
            $this->score_public !== null
            && $this->note_technique !== null
            && $this->note_originalite !== null
            && $this->note_presence !== null
            && $this->note_authenticite !== null
        ) {
            $this->note_jury = round(
                $this->note_technique + $this->note_originalite + $this->note_presence + $this->note_authenticite,
                2
            );
            $this->score_final = round($this->score_public + $this->note_jury, 2);
        }
    }
}
