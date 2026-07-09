<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Candidats extends Model
{
    use HasFactory;

    const CATEGORIES = [
        'Théâtre',
        'Percussions',
        'Musique',
        'Danse Traditionnelle',
        'Stylisme/Modélisme',
        'Arts Visuels',
    ];

    // Champs assignables en masse
    protected $fillable = [
        'nom', 'categorie', 'numero_scene', 'photo', 'biographie', 'nombre_votes',
    ];

    // Casts des attributs
    protected function casts(): array
    {
        return [
            'nombre_votes' => 'integer',
            'numero_scene' => 'integer',
        ];
    }

    // Relation : votes du candidat
    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class, 'candidat_id');
    }

    // Relation : médias associés
    public function medias(): HasMany
    {
        return $this->hasMany(Medias::class, 'candidat_id');
    }

    // Scope : votes confirmés uniquement
    public function confirmerVotes(): HasMany
    {
        return $this->votes()->where('statut', 'confirme');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->nom;
    }

    // Accesseur : URL complète de la photo
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo ? asset('storage/' . $this->photo) : '';
    }

    // Incrémente le compteur de votes
    public function getIncrementeVotes(int $quantite): void
    {
        $this->increment('nombre_votes', $quantite, []);
    }

    // Accesseur : rang dans la catégorie
    public function getRankAttribute(): ?int
    {
        return static::where('categorie', '=', $this->categorie, 'and')
            ->where('nombre_votes', '>', $this->nombre_votes, 'and')
            ->count() + 1;
    }

    // Scope : filtre par catégorie
    public function scopeByCategory(Builder $query, string $categorie): Builder
    {
        return $query->where('categorie', $categorie);
    }

    // Scope : trié par nombre de votes décroissant
    public function scopeOrderedByVotes(Builder $query): Builder
    {
        return $query->orderByDesc('nombre_votes');
    }

}
