<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Candidats extends Model
{
    Use HasFactory;

    // Liste officielle des catégories FITAB
    public const CATEGORIES = [
        'Théâtre',
        'Percussions',
        'Musique',
        'Danse Traditionnelle',
        'Stylisme/Modélisme',
        'Arts Visuels',
    ];

    Protected $fillable = [

        'nom', 'nom_scene', 'categorie', 'numero_scene', 'photo', 'biographie', 'nombre_votes',
        'note_maitrise', 'note_originalite', 'note_presence',

    ];

    protected function casts(): array
    {
        return [
            'nombre_votes' => 'integer',
            'numero_scene' => 'integer',
            'note_maitrise' => 'decimal:2',
            'note_originalite' => 'decimal:2',
            'note_presence' => 'decimal:2',
        ];
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class, 'candidat_id');
    }

    public function confirmerVotes(): HasMany
    {
        return $this->votes()->where('statut', 'confirme');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->nom_scene ?? $this->nom;
    }

    public function getPhotoUrlAttribute(): string
    {
        if (! $this->photo) {
            return '';
        }
        return Storage::url($this->photo);
    }

    public function getIncrementeVotes(int $quantite): void
    {
        $this->increment('nombre_votes', $quantite, []);
    }

    public function getRankAttribute(): ?int
    {
        return static::where('categorie', '=', $this->categorie, 'and')
            ->where('nombre_votes', '>', $this->nombre_votes, 'and')
            ->count() + 1;
    }

    public function scopeByCategory(Builder $query, string $categorie): Builder
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeOrderedByVotes(Builder $query)
    {
        return $query->orderByDesc('nombre_votes');
    }

    // Score pondéré jury (sur 85) : maitrise 30% + originalite 25% + presence 30%
    public function getScoreJuryAttribute(): ?float
    {
        if (is_null($this->note_maitrise) || is_null($this->note_originalite) || is_null($this->note_presence)) {
            return null;
        }
        return round(
            ($this->note_maitrise / 20 * 30) +
            ($this->note_originalite / 20 * 25) +
            ($this->note_presence / 20 * 30),
            2
        );
    }
}
