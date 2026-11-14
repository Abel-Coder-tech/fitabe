<?php

namespace App\Console\Commands;

use App\Models\Candidats;
use App\Models\Votes;
use App\Models\VoteLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuditerVotes extends Command
{
    protected $signature = 'votes:audit
                            {--heures=24 : Ancienneté minimale des votes en attente et fenêtre des logs}
                            {--corriger : Recalcule candidates.nombre_votes à partir des votes confirmés}
                            {--force : Confirme le recalcul sans poser de question}';

    protected $description = 'Contrôle la cohérence des votes : paiements en attente, compteurs candidats et erreurs récentes';

    /** Catégories de logs qui signalent une ovation potentiellement perdue. */
    private const CATEGORIES_ALERTE = [
        'signature_invalide',
        'montant_incoherent',
        'vote_non_trouve',
        'vote_incoherent',
        'verification_api_impossible',
    ];

    public function handle(): int
    {
        $heures = max(1, (int) $this->option('heures'));
        $cutoff = now()->subHours($heures);

        $alertes = 0;

        // 1. Votes payés potentiellement bloqués en attente.
        $enAttenteAnciens = Votes::query()
            ->enAttente()
            ->where('created_at', '<', $cutoff)
            ->orderBy('id')
            ->get(['id', 'candidate_id', 'quantite', 'montant', 'transaction_id', 'created_at']);

        $this->info("Votes en attente depuis plus de {$heures} h (à vérifier, abandons possibles) : {$enAttenteAnciens->count()}");

        if ($enAttenteAnciens->isNotEmpty()) {
            $this->table(
                ['Vote', 'Candidat', 'Ovations', 'Montant', 'Transaction', 'Créé le'],
                $enAttenteAnciens->take(50)->map(fn (Votes $vote) => [
                    $vote->id,
                    $vote->candidate_id,
                    $vote->quantite,
                    $vote->montant,
                    $vote->transaction_id,
                    optional($vote->created_at)->format('Y-m-d H:i'),
                ])->all()
            );

            if ($enAttenteAnciens->count() > 50) {
                $this->warn('Seuls les 50 premiers votes sont affichés.');
            }
        }

        // 2. Dérive des compteurs candidats (source de vérité = SUM des votes confirmés).
        $derives = Candidats::query()
            ->select('candidates.id', 'candidates.nom', 'candidates.nombre_votes')
            ->selectRaw(
                '(SELECT COALESCE(SUM(v.quantite), 0) FROM votes v WHERE v.candidate_id = candidates.id AND v.statut = ?) AS reel',
                ['confirme']
            )
            ->get()
            ->filter(fn (Candidats $candidat) => (int) $candidat->nombre_votes !== (int) $candidat->reel)
            ->values();

        $this->info("Compteurs candidats incohérents : {$derives->count()}");

        $compteursIncoherents = $derives->count();

        if ($derives->isNotEmpty()) {
            $this->table(
                ['Candidat', 'Nom', 'Compteur', 'Réel', 'Écart'],
                $derives->map(fn (Candidats $candidat) => [
                    $candidat->id,
                    $candidat->nom,
                    $candidat->nombre_votes,
                    $candidat->reel,
                    (int) $candidat->nombre_votes - (int) $candidat->reel,
                ])->all()
            );

            if ($this->option('corriger')) {
                $confirme = $this->option('force')
                    || $this->confirm('Recalculer '.$derives->count().' compteur(s) depuis les votes confirmés ?', false);

                if (! $confirme) {
                    $this->warn('Correction annulée : aucun compteur modifié.');
                } else {
                    DB::transaction(function () use ($derives) {
                        foreach ($derives as $candidat) {
                            Candidats::query()
                                ->whereKey($candidat->id)
                                ->update(['nombre_votes' => (int) $candidat->reel]);
                        }
                    });

                    $this->warn("{$derives->count()} compteur(s) recalculé(s) depuis les votes confirmés.");
                    $compteursIncoherents = 0;
                }
            }
        }

        $alertes += $compteursIncoherents;

        // 3. Erreurs récentes qui indiquent une ovation non comptée.
        $logs = VoteLog::query()
            ->whereIn('categorie', self::CATEGORIES_ALERTE)
            ->where('created_at', '>=', $cutoff)
            ->selectRaw('categorie, COUNT(*) AS total')
            ->groupBy('categorie')
            ->pluck('total', 'categorie');

        $this->info("Erreurs de confirmation sur les {$heures} dernières heures : ".$logs->sum());

        if ($logs->isNotEmpty()) {
            $alertes += (int) $logs->sum();

            $this->table(
                ['Catégorie', 'Nombre'],
                $logs->map(fn ($total, $categorie) => [$categorie, $total])->values()->all()
            );
        }

        if ($alertes > 0) {
            Log::warning('Audit des votes : anomalies détectées.', [
                'votes_en_attente' => $enAttenteAnciens->count(),
                'compteurs_derives' => $compteursIncoherents,
                'logs_erreurs' => $logs->sum(),
                'heures' => $heures,
                'correction_compteurs' => (bool) $this->option('corriger'),
            ]);

            $this->newLine();
            $this->warn("{$alertes} anomalie(s) détectée(s). Consultez storage/logs/laravel.log.");

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Aucune anomalie détectée.');

        return self::SUCCESS;
    }
}
