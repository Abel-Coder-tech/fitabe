<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medias extends Model
{
    // Champs assignables en masse
    protected $fillable = [
        'titre',
        'description',
        'type',
        'url',
        'lien_youtube',
        'annee_edition',
        'ordre_affichage',
    ];

    // Accesseur : URL de la miniature (YouTube ou locale)
    public function getThumbnailAttribute(): string
    {
        if ($this->type === 'video') {
            return 'https://img.youtube.com/vi/' . $this->youtube_id . '/mqdefault.jpg';
        }
        return $this->url ? asset('storage/' . $this->url) : '';
    }

    // Accesseur : extrait l'ID YouTube depuis le lien
    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->lien_youtube) return null;
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->lien_youtube, $matches);
        return $matches[1] ?? null;
    }
}
