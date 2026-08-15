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
        'frais',
        'statut',
        'payment_method',
        'moyen_paiement',
        'operateur',
        'pays',
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
            'frais' => 'integer',
            'webhook_recu_le' => 'datetime',
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

    public function scopeRejete(Builder $query): Builder
    {
        return $query->where('statut', 'rejete');
    }

    public function marquerConfirme(string $transactionId, string $paymentMethod, ?string $telephone = null, ?string $email = null, ?string $moyenPaiement = null, ?int $frais = null, ?string $operateur = null, ?string $pays = null): void
    {
        $this->update(array_filter([
            'statut' => 'confirme',
            'transaction_id' => $transactionId,
            'payment_method' => $paymentMethod,
            'telephone' => $telephone,
            'email' => $email,
            'moyen_paiement' => $moyenPaiement,
            'frais' => $frais,
            'operateur' => $operateur,
            'pays' => $pays,
            'webhook_recu_le' => now(),
        ]));

        $this->candidat?->getIncrementeVotes($this->quantite);
    }

    public function marquerRejete(?string $transactionId = null): void
    {
        $this->update(array_filter([
            'statut' => 'rejete',
            'transaction_id' => $transactionId,
            'webhook_recu_le' => now(),
        ]));
    }
}
