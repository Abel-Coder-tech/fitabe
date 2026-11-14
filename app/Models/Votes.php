<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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

    /**
     * Confirme le vote une seule fois, de façon atomique.
     *
     * Le passage par une transaction + verrou de ligne + filtre
     * « statut = en_attente » garantit qu'un webhook, le retour
     * navigateur et le cron ne peuvent pas incrémenter deux fois le
     * compteur du candidat s'ils traitent la même transaction en parallèle.
     *
     * @return bool true si le vote vient d'être confirmé, false s'il était déjà traité.
     */
    public function marquerConfirme(string $transactionId, string $paymentMethod, ?string $telephone = null, ?string $email = null, ?string $moyenPaiement = null, ?int $frais = null, ?string $operateur = null, ?string $pays = null): bool
    {
        return DB::transaction(function () use ($transactionId, $paymentMethod, $telephone, $email, $moyenPaiement, $frais, $operateur, $pays) {
            $vote = static::query()->whereKey($this->getKey())->lockForUpdate()->first();

            if (! $vote || $vote->statut !== 'en_attente') {
                return false;
            }

            $attributs = array_filter([
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
            ]);

            $modifie = static::query()
                ->whereKey($vote->getKey())
                ->where('statut', 'en_attente')
                ->update($attributs);

            if ($modifie === 0) {
                return false;
            }

            $this->forceFill($attributs);
            $this->syncOriginal();

            // Incrémentation uniquement après la mise à jour effective du vote.
            if ($vote->candidate_id) {
                Candidats::query()
                    ->whereKey($vote->candidate_id)
                    ->increment('nombre_votes', (int) $vote->quantite);
            }

            return true;
        });
    }

    /**
     * Rejette le vote une seule fois, et uniquement s'il est encore en attente.
     *
     * @return bool true si le rejet vient d'être appliqué, false sinon.
     */
    public function marquerRejete(?string $transactionId = null): bool
    {
        return DB::transaction(function () use ($transactionId) {
            $attributs = array_filter([
                'statut' => 'rejete',
                'transaction_id' => $transactionId,
                'webhook_recu_le' => now(),
            ]);

            $modifie = static::query()
                ->whereKey($this->getKey())
                ->where('statut', 'en_attente')
                ->update($attributs);

            if ($modifie === 0) {
                return false;
            }

            $this->forceFill($attributs);
            $this->syncOriginal();

            return true;
        });
    }
}
