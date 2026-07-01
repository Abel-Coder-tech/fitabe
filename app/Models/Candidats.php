<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Candidats extends Model
{
    use HasFactory;

    protected $fillable = [

        'nom', 'nom_scene', 'categorie', 'numero_scene', 'photo', 'biographie', 'nombre_votes',

    ];

    protected function casts(): array
    {
        return [
            'nombre_votes' => 'integer',
            'numero_scene' => 'integer',
        ];
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class, 'candidat_id');
    }

    public function medias(): HasMany
    {
        return $this->hasMany(Medias::class, 'candidat_id');
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
        return asset('storage/photos/' . $this->photo);
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

    public function scopeOrderedByVotes(Builder $query): Builder
    {
        return $query->orderByDesc('nombre_votes');
    }

}
