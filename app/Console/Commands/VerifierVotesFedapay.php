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
                $operateur = \App\Support\FedaPayInfos::operateur($mode);

                $vote = Votes::find($voteId);
                if (! $vote) {
                    $introuvables++;
                    continue;
                }

                if ($vote->statut === 'confirme') {
                    $dejaOk++;
                    continue;
                }

                if ($status === 'approved') {
                    $this->line("Vote #{$voteId} — FedaPay {$txnId} — {$txn['amount']} FCFA — {$mode}");

                    if ($dry) {
                        $this->info("  → CONFIRMABLE (dry)");
                    } else {
                        $telephone = $vote->client_telephone;
                        $vote->marquerConfirme($txnId, 'fedapay', $telephone, $email, $mode, $frais, $operateur, null);

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
                    }
                    $confirmes++;
                } elseif (in_array($status, ['canceled', 'declined', 'failed'], true)) {
                    $error = $txn['last_error_code'] ?? '';
                    $this->line("Vote #{$voteId} — {$status} ({$error})");

                    if (! $dry && $vote->statut !== 'rejete') {
                        $vote->marquerRejete($txnId);
                        $this->warn("  → REJETÉ");
                    }
                    $rejetes++;
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
