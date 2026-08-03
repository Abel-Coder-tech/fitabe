<?php

namespace App\Services;

use App\Models\Candidats;
use App\Models\Resultat;
use Illuminate\Support\Facades\DB;

class ResultatService
{
    // Seuil de votes pour obtenir le maximum de points publics (15)
    public const SEUIL_VOTES = 2500;

    // Maximum de points publics (ovations)
    public const SCORE_PUBLIC_MAX = 15;

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
                        'note_perfection' => null,
                        'score_public' => null,
                        'score_final' => null,
                    ]
                );
            }
        }

        $this->calculerScoresPublics($anneeEdition);
    }

    // Calcule les scores publics (ovations) pour une édition : 15 pts max à partir de 2500 votes, sinon proportionnel
    public function calculerScoresPublics(string $anneeEdition): void
    {
        $resultats = Resultat::byEdition($anneeEdition)->get();

        foreach ($resultats as $r) {
            $votes = (int) $r->nombre_votes;
            $scorePublic = $votes >= self::SEUIL_VOTES
                ? self::SCORE_PUBLIC_MAX
                : round(($votes / self::SEUIL_VOTES) * self::SCORE_PUBLIC_MAX, 2);

            $r->score_public = $scorePublic;
            $r->recalculerScoreFinal();
            $r->save();
        }
    }
}
