<?php

namespace App\Console\Commands;

use App\Models\Votes;
use App\Support\FedaPayInfos;
use Illuminate\Console\Command;

class BackfillOperateurs extends Command
{
    protected $signature = 'votes:backfill-operateurs';
    protected $description = 'Met à jour l\'opérateur des votes confirmés qui n\'en ont pas encore';

    public function handle(): int
    {
        $votes = Votes::where('statut', 'confirme')
            ->whereNull('operateur')
            ->whereNotNull('moyen_paiement')
            ->get();

        if ($votes->isEmpty()) {
            $this->info('Aucun vote à mettre à jour.');
            return self::SUCCESS;
        }

        $maj = 0;

        foreach ($votes as $vote) {
            $operateur = FedaPayInfos::operateur($vote->moyen_paiement);
            if ($operateur) {
                $vote->update(['operateur' => $operateur]);
                $maj++;
            }
        }

        $this->info("Backfill terminé : {$maj} vote(s) mis à jour sur " . $votes->count() . " candidate(s).");
        return self::SUCCESS;
    }
}
