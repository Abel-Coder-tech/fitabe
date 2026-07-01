<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medias extends Model
{
    protected $fillable = [
        'titre',
        'type',
        'url',
        'candidat_id',
        'taille',
    ];

    protected function casts(): array
    {
        return [
            'taille' => 'integer',
        ];
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidats::class, 'candidat_id');
    }

    public function getMediaUrlAttribute(): string
    {
        if ($this->type === 'video') {
            return $this->url;
        }
        return asset('storage/' . $this->url);
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if ($this->type !== 'video') return null;
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->url, $matches);
        return $matches[1] ?? null;
    }

    public function getThumbnailAttribute(): ?string
    {
        if ($this->type === 'video' && $this->getYoutubeIdAttribute()) {
            return 'https://img.youtube.com/vi/' . $this->getYoutubeIdAttribute() . '/hqdefault.jpg';
        }
        return $this->media_url;
    }

    public function getAnneeAttribute(): string
    {
        return $this->created_at->format('Y');
    }
}
