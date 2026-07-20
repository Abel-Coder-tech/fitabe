<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Votes extends Model
{
    protected $fillable = [
        'candidate_id',
        'email',
        'telephone',
        'quantite',
        'montant',
        'statut',
        'payment_method',
        'moyen_paiement',
        'transaction_id',
        'adresse_ip',
        'webhook_recu_le',
    ];

    protected function casts(): array
    {
        return [
            'statut' => 'string',
            'quantite' => 'integer',
            'montant' => 'integer',
        ];
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidats::class, 'candidate_id');
    }

    public function scopeConfirme(Builder $query): Builder
    {
        return $query->where('statut', 'confirme');
    }

    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', 'en_attente');
    }

    public function marquerConfirme(string $transactionId, string $paymentMethod, ?string $telephone = null, ?string $email = null, ?string $moyenPaiement = null): void
    {
        $this->update(array_filter([
            'statut' => 'confirme',
            'transaction_id' => $transactionId,
            'payment_method' => $paymentMethod,
            'telephone' => $telephone,
            'email' => $email,
            'moyen_paiement' => $moyenPaiement,
            'webhook_recu_le' => now(),
        ]));

        $this->candidat?->getIncrementeVotes($this->quantite);
    }
}
