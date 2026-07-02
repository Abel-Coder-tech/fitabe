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

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidats::class);
    }
}
