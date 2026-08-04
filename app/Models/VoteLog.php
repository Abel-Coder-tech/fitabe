<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VoteLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'statut',
        'categorie',
        'message',
        'transaction_id',
        'vote_id',
        'montant',
        'contexte',
        'created_at',
    ];

    public function scopeRecents(Builder $query): Builder
    {
        return $query->latest('created_at');
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
