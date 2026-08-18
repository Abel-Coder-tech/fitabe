<?php

namespace App\Console\Commands;

use App\Models\Candidats;
use App\Models\Votes;
use Illuminate\Console\Command;

class RecompterVotes extends Command
{
    protected $signature = 'votes:recompter';
    protected $description = 'Recalcule le nombre_votes de chaque candidat depuis les ovations confirmées';

    public function handle(): int
    {
        $candidats = Candidats::all();
        $total = 0;

        foreach ($candidats as $candidat) {
            $somme = Votes::where('candidat_id', $candidat->id)
                ->where('statut', 'confirme')
                ->sum('quantite');

            $ancien = $candidat->nombre_votes;

            if ($ancien !== (int) $somme) {
                $candidat->update(['nombre_votes' => (int) $somme]);
                $this->line("  {$candidat->display_name}: {$ancien} → {$somme}");
            }

            $total++;
        }

        $this->info("Recomptage terminé : {$total} candidat(s) vérifié(s).");
        return self::SUCCESS;
    }
}
