<?php

namespace App\Console\Commands;

use App\Models\VoteLog;
use App\Models\Votes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VerifierVotesFedapay extends Command
{
    protected $signature = 'votes:verifier-fedapay
                            {--dry : Affiche les votes à confirmer sans rien modifier}
                            {--limit=50 : Nombre max de votes à traiter par appel}';

    protected $description = 'Vérifie les votes en attente via l\'API FedaPay et confirme ceux approuvés';

    public function handle(): int
    {
        $dry = $this->option('dry');
        $limit = (int) $this->option('limit');

        $secretKey = config('services.fedapay.secret_key');
        $mode = config('services.fedapay.mode', 'live');
        $baseUrl = $mode === 'sandbox'
            ? 'https://sandbox-api.fedapay.com/v1'
            : 'https://api.fedapay.com/v1';

        if (! $secretKey) {
            $this->error('Clé secrète FedaPay non configurée (FEDAPAY_SECRET_KEY).');
            return self::FAILURE;
        }

        $votes = Votes::enAttente()
            ->where('transaction_id', 'not like', 'pending_%')
            ->whereNotNull('transaction_id')
            ->orderBy('created_at')
            ->limit($limit)
            ->get();

        if ($votes->isEmpty()) {
            $this->info('Aucun vote en attente avec un vrai transaction_id FedaPay.');
            return self::SUCCESS;
        }

        $this->info("{$votes->count()} vote(s) à vérifier...");
        $this->newLine();

        $confirmes = 0;
        $echecs = 0;
        $erreurs = 0;
        $attente = 0;

        foreach ($votes as $vote) {
            $txnId = $vote->transaction_id;

            $this->line("Vote #{$vote->id} — txn {$txnId} — {$vote->montant} FCFA");

            try {
                $response = Http::withToken($secretKey)
                    ->accept('application/json')
                    ->get("{$baseUrl}/transactions/{$txnId}");

                if ($response->failed()) {
                    $this->warn("  → API erreur HTTP {$response->status()}");
                    $erreurs++;
                    continue;
                }

                $data = $response->json();
                $status = $data['status'] ?? 'unknown';

                if (in_array($status, ['approved', 'completed', 'accepted'], true)) {
                    if ($dry) {
                        $this->info("  → CONFIRMÉ (dry run — rien fait)");
                    } else {
                        $telephone = $data['phone']
                            ?? $data['customer']['phone_number']
                            ?? $data['customer']['phone']
                            ?? null;
                        $email = $data['customer']['email'] ?? null;

                        $rawPm = $data['payment_method'] ?? null;
                        $moyenPaiement = is_string($rawPm)
                            ? $rawPm
                            : (is_array($rawPm) ? ($rawPm['type'] ?? null) : null);

                        $modePm = is_array($rawPm)
                            ? ($rawPm['mode'] ?? $rawPm['type'] ?? null)
                            : ($rawPm ?? $data['mode'] ?? null);
                        $modePm = is_string($modePm) ? $modePm : null;

                        $operateur = \App\Support\FedaPayInfos::operateur($modePm);
                        $pays = \App\Support\FedaPayInfos::pays($data['customer']['country'] ?? null, $telephone);
                        $frais = \App\Support\FedaPayInfos::frais($data);

                        $vote->marquerConfirme($txnId, 'fedapay', $telephone, $email, $moyenPaiement, $frais, $operateur, $pays);

                        VoteLog::create([
                            'type' => 'webhook',
                            'statut' => 'ok',
                            'categorie' => 'confirme',
                            'message' => "Vote confirmé via vérification manuelle (commande artisan).",
                            'transaction_id' => $txnId,
                            'vote_id' => $vote->id,
                            'montant' => $vote->montant,
                            'contexte' => json_encode(['source' => 'artisan_verifier', 'api_status' => $status], JSON_UNESCAPED_UNICODE),
                        ]);

                        $this->info("  → CONFIRMÉ (compteur incrémenté)");
                    }
                    $confirmes++;
                } elseif (in_array($status, ['declined', 'canceled', 'refunded', 'expired', 'rejected', 'failed'], true)) {
                    if (! $dry) {
                        $vote->marquerRejete($txnId);

                        VoteLog::create([
                            'type' => 'webhook',
                            'statut' => 'ok',
                            'categorie' => 'rejete',
                            'message' => "Paiement {$status} — rejeté via vérification manuelle.",
                            'transaction_id' => $txnId,
                            'vote_id' => $vote->id,
                            'montant' => $vote->montant,
                        ]);
                    }
                    $this->warn("  → REJETÉ ({$status})");
                    $echecs++;
                } else {
                    $this->line("  → Toujours en cours ({$status})");
                    $attente++;
                }
            } catch (\Throwable $e) {
                $this->error("  → Exception: {$e->getMessage()}");
                Log::error('VerifierVotesFedapay exception', [
                    'vote_id' => $vote->id,
                    'transaction_id' => $txnId,
                    'error' => $e->getMessage(),
                ]);
                $erreurs++;
            }
        }

        $this->newLine();
        $this->table(
            ['Résultat', 'Nombre'],
            [
                ['Confirmés', $confirmes],
                ['Rejetés', $echecs],
                ['En attente', $attente],
                ['Erreurs', $erreurs],
            ]
        );

        if ($dry) {
            $this->warn('Mode dry — aucun vote n\'a été modifié.');
        }

        return self::SUCCESS;
    }
}
