<?php

namespace App\Services;

use App\Models\Candidats;
use App\Models\Resultat;
use Illuminate\Support\Facades\DB;

class ResultatService
{
    // Génère les résultats pour une édition (top 3 par catégorie)
    public function generer(string $anneeEdition): void
    {
        $categories = Candidats::select('categorie')->distinct()->pluck('categorie');

        foreach ($categories as $categorie) {
            $top = Candidats::byCategory($categorie)
                ->orderedByVotes()
                ->take(3)
                ->get();

            foreach ($top as $index => $candidat) {
                $prix = $index + 1;

                Resultat::updateOrCreate(
                    [
                        'annee_edition' => $anneeEdition,
                        'categorie' => $categorie,
                        'prix' => $prix,
                    ],
                    [
                        'candidat_nom' => $candidat->display_name,
                        'candidat_photo' => $candidat->photo,
                        'nombre_votes' => $candidat->nombre_votes,
                        'note_jury' => null,
                        'note_technique' => null,
                        'note_originalite' => null,
                        'note_presence' => null,
                        'score_public' => null,
                        'score_final' => null,
                    ]
                );
            }
        }

        $this->calculerScoresPublics($anneeEdition);
    }

    // Calcule les scores publics (normalisés sur 20) pour une édition
    public function calculerScoresPublics(string $anneeEdition): void
    {
        $categories = Resultat::byEdition($anneeEdition)->select('categorie')->distinct()->pluck('categorie');

        foreach ($categories as $categorie) {
            $maxVotes = Resultat::byEdition($anneeEdition)
                ->byCategorie($categorie)
                ->max('nombre_votes');

            if ($maxVotes <= 0) continue;

            $resultats = Resultat::byEdition($anneeEdition)
                ->byCategorie($categorie)
                ->get();

            foreach ($resultats as $r) {
                $scorePublic = round(($r->nombre_votes / $maxVotes) * 20, 2);
                $r->score_public = $scorePublic;
                $r->recalculerScoreFinal();
                $r->save();
            }
        }
    }
}
