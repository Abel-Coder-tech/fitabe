<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Candidats extends Model
{
    protected $table = 'candidates';

    Use HasFactory;

    public const CATEGORIES = [
        'théâtre',
        'danse',
        'musique',
        'percussion',
        'arts visuels',
        'stylisme/modélisme',
    ];

    public const CATEGORY_COLORS = [
        'théâtre' => '#C0392B',
        'danse' => '#8E44AD',
        'musique' => '#1F6FB2',
        'percussion' => '#E67E22',
        'arts visuels' => '#16A085',
        'stylisme/modélisme' => '#B8860B',
    ];

    Protected $fillable = [
        'nom', 'nom_scene', 'categorie', 'numero_scene', 'photo', 'biographie', 'nombre_votes', 'note_jury',
    ];

    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return [
            'nombre_votes' => 'integer',
            'numero_scene' => 'integer',
            'note_jury' => 'decimal:2',
        ];
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class, 'candidate_id');
    }

    public function confirmerVotes(): HasMany
    {
        return $this->votes()->where('statut', 'confirme');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->nom_scene ?: $this->nom;
    }

    public function getPhotoUrlAttribute(): string
    {
        $photo = $this->photo;

        if (! $photo) {
            return asset('images/hero.jpg');
        }

        $photo = str_replace('\\', '/', $photo);

        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }

        $photo = ltrim($photo, '/');
        $photo = preg_replace('#^storage/#', '', $photo);

        return Storage::disk('public')->url($photo);
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

    public function scopeOrderedByScene(Builder $query)
    {
        return $query->orderBy('numero_scene');
    }

    // Nombre de places configuré pour la catégorie du candidat
    public function getPlacesAttribute(): int
    {
        return PlacesCategorie::pour($this->categorie);
    }
}
