<?php

namespace App\Console\Commands;

use App\Models\VoteLog;
use App\Models\Votes;
use App\Support\FedaPayInfos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VerifierVotesFedapay extends Command
{
    protected $signature = 'votes:verifier-fedapay
                            {--dry : Affiche les votes à confirmer sans rien modifier}
                            {--sans-rejet : Confirme uniquement les paiements prouvés, ne rejette jamais un vote}
                            {--pages=10 : Nombre max de pages API à parcourir (25 résultats/page)}';

    protected $description = 'Interroge l\'API FedaPay, croise par vote_id et confirme les paiements approuvés';

    public function handle(): int
    {
        $dry = $this->option('dry');
        $maxPages = (int) $this->option('pages');

        $secretKey = config('services.fedapay.secret_key');
        $mode = config('services.fedapay.mode', 'live');
        $baseUrl = $mode === 'sandbox'
            ? 'https://sandbox-api.fedapay.com/v1'
            : 'https://api.fedapay.com/v1';

        if (! $secretKey) {
            $this->error('Clé secrète FedaPay non configurée (FEDAPAY_SECRET_KEY).');
            return self::FAILURE;
        }

        $confirmes = 0;
        $rejetes = 0;
        $attente = 0;
        $dejaOk = 0;
        $introuvables = 0;
        $incoherents = 0;
        $echecsIgnores = 0;
        $erreurs = 0;

        $page = 1;

        $this->info("Interrogation de l'API FedaPay...");
        $this->newLine();

        while ($page <= $maxPages) {
            $response = Http::timeout(30)
                ->retry(2, 3000)
                ->withToken($secretKey)
                ->accept('application/json')
                ->get("{$baseUrl}/transactions/search", [
                    'page' => $page,
                    'limit' => 25,
                ]);

            if ($response->failed()) {
                $this->error("Erreur API page {$page}: HTTP {$response->status()}");
                $erreurs++;
                break;
            }

            $body = $response->json();
            $transactions = $body['v1/transactions'] ?? $body['data'] ?? [];

            if (empty($transactions)) {
                break;
            }

            foreach ($transactions as $txn) {
                $voteId = $txn['custom_metadata']['vote_id'] ?? null;

                if (! $voteId) {
                    $desc = $txn['description'] ?? '';
                    if (preg_match('/#(\d+)/', $desc, $m)) {
                        $voteId = $m[1];
                    }
                }

                if (! $voteId) {
                    continue;
                }

                $voteId = (int) $voteId;
                $status = $txn['status'] ?? 'unknown';
                $txnId = (string) ($txn['id'] ?? '');
                $mode = $txn['mode'] ?? null;
                $frais = isset($txn['fees']) ? (int) round((float) $txn['fees']) : null;

                $paidCustomer = $txn['metadata']['paid_customer'] ?? null;
                $email = $paidCustomer['email'] ?? null;
                $operateur = FedaPayInfos::operateur($mode);

                $vote = Votes::find($voteId);
                if (! $vote) {
                    $introuvables++;
                    continue;
                }

                if ($vote->statut !== 'en_attente') {
                    if ($vote->statut === 'confirme') {
                        $dejaOk++;
                    }
                    continue;
                }

                if (in_array($status, FedaPayInfos::STATUTS_CONFIRMANTS, true)) {
                    $montantPaye = isset($txn['amount']) ? (int) $txn['amount'] : null;

                    // Un paiement confirmé dont le montant ne correspond pas au vote
                    // ne doit jamais être compté (protection contre les manipulations).
                    if ($montantPaye !== null && $montantPaye !== (int) $vote->montant) {
                        $this->line("Vote #{$voteId} — FedaPay {$txnId} — {$montantPaye} FCFA");
                        $this->warn("  → MONTANT INCOHÉRENT (attendu {$vote->montant}) — non compté");

                        if (! $dry) {
                            VoteLog::create([
                                'type' => 'webhook',
                                'statut' => 'erreur',
                                'categorie' => 'montant_incoherent',
                                'message' => "Montant FedaPay ({$montantPaye}) différent du montant du vote ({$vote->montant}) : ovation non comptée.",
                                'transaction_id' => $txnId,
                                'vote_id' => $vote->id,
                                'montant' => $vote->montant,
                                'contexte' => json_encode([
                                    'source' => 'artisan_verifier',
                                    'api_status' => $status,
                                    'montant_paye' => $montantPaye,
                                ], JSON_UNESCAPED_UNICODE),
                            ]);
                        }

                        $incoherents++;
                        continue;
                    }

                    $this->line("Vote #{$voteId} — FedaPay {$txnId} — {$txn['amount']} FCFA — {$mode}");

                    if ($dry) {
                        $this->info("  → CONFIRMABLE (dry)");
                        $confirmes++;
                    } else {
                        $telephone = $vote->telephone;
                        $confirme = $vote->marquerConfirme($txnId, 'fedapay', $telephone, $email, $mode, $frais, $operateur, null);

                        if ($confirme) {
                            VoteLog::create([
                                'type' => 'webhook',
                                'statut' => 'ok',
                                'categorie' => 'confirme',
                                'message' => "Vote confirmé via vérification API FedaPay (commande artisan).",
                                'transaction_id' => $txnId,
                                'vote_id' => $vote->id,
                                'montant' => $vote->montant,
                                'contexte' => json_encode([
                                    'source' => 'artisan_verifier',
                                    'api_status' => $status,
                                    'fedapay_id' => $txn['id'],
                                ], JSON_UNESCAPED_UNICODE),
                            ]);

                            $this->info("  → CONFIRMÉ ✓");
                            $confirmes++;
                        } else {
                            // Un autre traitement (webhook, /merci, autre cron) l'a déjà confirmé.
                            $dejaOk++;
                        }
                    }
                } elseif (in_array($status, FedaPayInfos::STATUTS_ECHEC, true)) {
                    $error = $txn['last_error_code'] ?? '';
                    $this->line("Vote #{$voteId} — {$status} ({$error})");

                    if ($this->option('sans-rejet')) {
                        // Mode prudent utilisé par les tâches planifiées : aucun
                        // vote n'est rejeté automatiquement, même sur un statut
                        // d'échec. Un rejet reste une décision manuelle.
                        $this->warn("  → ÉCHEC (non rejeté : --sans-rejet)");
                        $echecsIgnores++;
                    } else {
                        if (! $dry && $vote->statut !== 'rejete') {
                            $vote->marquerRejete($txnId);
                            $this->warn("  → REJETÉ");
                        }
                        $rejetes++;
                    }
                } else {
                    $attente++;
                }
            }

            $meta = $body['meta'] ?? [];
            if (empty($meta['next_page']) || $page >= ($meta['total_pages'] ?? 1)) {
                break;
            }
            $page++;
        }

        $this->newLine();
        $this->table(
            ['Résultat', 'Nombre'],
            [
                ['Confirmés (nouveaux)', $confirmes],
                ['Déjà confirmés', $dejaOk],
                ['Rejetés', $rejetes],
                ['Échecs non rejetés', $echecsIgnores],
                ['Montants incohérents', $incoherents],
                ['Toujours en attente', $attente],
                ['Votes introuvables', $introuvables],
                ['Erreurs API', $erreurs],
            ]
        );

        if ($dry) {
            $this->warn('Mode dry — aucun vote n\'a été modifié.');
        }

        return self::SUCCESS;
    }
}
