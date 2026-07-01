<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Votes extends Model
{
    protected $fillable = [
        'candidat_id',
        'votant_nom',
        'votant_email',
        'votant_telephone',
        'statut',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'statut' => 'string',
        ];
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidats::class, 'candidat_id');
    }

    public function scopeConfirme(Builder $query): Builder
    {
        return $query->where('statut', 'confirme');
    }

    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', 'en_attente');
    }
}
