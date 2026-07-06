<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Votes extends Model
{
    // Champs assignables en masse
    protected $fillable = [
        'candidat_id',
        'votant_nom',
        'votant_email',
        'votant_telephone',
        'statut',
        'ip_address',
        'quantite',
        'montant',
        'payment_method',
        'transaction_id',
    ];

    // Casts des attributs
    protected function casts(): array
    {
        return [
            'statut' => 'string',
            'quantite' => 'integer',
            'montant' => 'integer',
        ];
    }

    // Relation : candidat concerné
    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidats::class, 'candidat_id');
    }

    // Scope : votes confirmés
    public function scopeConfirme(Builder $query): Builder
    {
        return $query->where('statut', 'confirme');
    }

    // Scope : votes en attente
    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', 'en_attente');
    }

    // Marque le vote comme confirmé et incrémente le compteur
    public function marquerConfirme(string $transactionId, string $paymentMethod): void
    {
        $this->update([
            'statut' => 'confirme',
            'transaction_id' => $transactionId,
            'payment_method' => $paymentMethod,
        ]);

        $this->candidat?->getIncrementeVotes($this->quantite);
    }
}
