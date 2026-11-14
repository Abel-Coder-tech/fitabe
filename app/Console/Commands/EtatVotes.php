<?php

namespace App\Console\Commands;

use App\Models\Candidats;
use App\Models\Votes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EtatVotes extends Command
{
    protected $signature = 'votes:etat
                            {--enregistrer= : Enregistre une empreinte JSON à ce chemin}
                            {--comparer= : Compare l\'état courant à une empreinte JSON}';

    protected $description = 'Affiche ou compare une empreinte en lecture seule des votes et des compteurs';

    public function handle(): int
    {
        $etat = $this->etat();

        if ($chemin = $this->option('comparer')) {
            return $this->comparer($etat, (string) $chemin);
        }

        $this->afficher($etat);

        if ($chemin = $this->option('enregistrer')) {
            File::ensureDirectoryExists(dirname((string) $chemin));
            File::put((string) $chemin, json_encode($etat, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $this->newLine();
            $this->info("Empreinte enregistrée dans {$chemin}");
        }

        return self::SUCCESS;
    }

    /**
     * Photographie en lecture seule : aucune écriture en base.
     */
    private function etat(): array
    {
        $parStatut = Votes::query()
            ->selectRaw('statut, COUNT(*) AS nb, COALESCE(SUM(quantite), 0) AS ovations, COALESCE(SUM(montant), 0) AS montant')
            ->groupBy('statut')
            ->orderBy('statut')
            ->get()
            ->mapWithKeys(fn ($ligne) => [
                (string) $ligne->statut => [
                    'nb' => (int) $ligne->nb,
                    'ovations' => (int) $ligne->ovations,
                    'montant' => (int) $ligne->montant,
                ],
            ])
            ->all();

        $confirmes = Votes::query()
            ->confirme()
            ->orderBy('id')
            ->get(['id', 'candidate_id', 'quantite', 'montant', 'transaction_id'])
            ->map(fn (Votes $vote) => implode(':', [
                $vote->id,
                $vote->candidate_id,
                $vote->quantite,
                $vote->montant,
                $vote->transaction_id,
            ]))
            ->implode('|');

        $candidats = Candidats::query()
            ->orderBy('id')
            ->get(['id', 'nombre_votes'])
            ->map(fn (Candidats $candidat) => $candidat->id.':'.$candidat->nombre_votes)
            ->implode('|');

        return [
            'genere_le' => now()->toDateTimeString(),
            'votes_par_statut' => $parStatut,
            'total_votes' => Votes::count(),
            'total_candidats' => Candidats::count(),
            'empreinte_votes_confirmes' => hash('sha256', $confirmes),
            'empreinte_compteurs_candidats' => hash('sha256', $candidats),
        ];
    }

    private function afficher(array $etat): void
    {
        $this->info('Votes par statut :');

        $lignes = [];

        foreach ($etat['votes_par_statut'] as $statut => $valeurs) {
            $lignes[] = [$statut, $valeurs['nb'], $valeurs['ovations'], $valeurs['montant']];
        }

        $this->table(['Statut', 'Votes', 'Ovations', 'Montant'], $lignes);

        $this->line('Total votes : '.$etat['total_votes']);
        $this->line('Total candidats : '.$etat['total_candidats']);
        $this->line('Empreinte votes confirmés : '.$etat['empreinte_votes_confirmes']);
        $this->line('Empreinte compteurs candidats : '.$etat['empreinte_compteurs_candidats']);
    }

    private function comparer(array $apres, string $chemin): int
    {
        if (! File::exists($chemin)) {
            $this->error("Fichier d'empreinte introuvable : {$chemin}");

            return self::FAILURE;
        }

        $avant = json_decode((string) File::get($chemin), true);

        if (! is_array($avant) || ! isset($avant['empreinte_votes_confirmes'])) {
            $this->error("Fichier d'empreinte invalide : {$chemin}");

            return self::FAILURE;
        }

        $differences = 0;

        if ($avant['empreinte_votes_confirmes'] !== $apres['empreinte_votes_confirmes']) {
            $this->error('ALERTE : les votes confirmés ont changé.');
            $differences++;
        }

        if (($avant['empreinte_compteurs_candidats'] ?? null) !== $apres['empreinte_compteurs_candidats']) {
            $this->warn('Les compteurs candidats ont changé.');
            $differences++;
        }

        foreach (['votes_par_statut', 'total_votes', 'total_candidats'] as $cle) {
            if (($avant[$cle] ?? null) !== $apres[$cle]) {
                $this->warn("La valeur « {$cle} » a changé.");
                $differences++;
            }
        }

        if ($differences === 0) {
            $this->info('État identique : aucune ovation, aucun compteur n\'a changé.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->warn("{$differences} différence(s) détectée(s). Vérifiez le détail ci-dessus avant de continuer.");

        return self::FAILURE;
    }
}
