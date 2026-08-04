<?php

namespace App\Services;

use App\Models\Candidats;
use App\Models\Resultat;
use Illuminate\Support\Facades\DB;

class ResultatService
{
    // Seuil de votes pour obtenir le maximum de points publics (10)
    public const SEUIL_VOTES = 2500;

    // Maximum de points publics (ovations)
    public const SCORE_PUBLIC_MAX = 10;

    // Génère les résultats pour une édition (les 3 finalistes par catégorie, triés par ovations à la génération)
    public function generer(string $anneeEdition): void
    {
        // Conserve l'état de publication avant de repartir d'une page blanche
        $avaitDesPublies = Resultat::byEdition($anneeEdition)->where('publie', true)->exists();
        Resultat::byEdition($anneeEdition)->delete();

        $categories = Candidats::select('categorie')->distinct()->pluck('categorie');

        foreach ($categories as $categorie) {
            $finalistes = Candidats::byCategory($categorie)
                ->orderedByVotes()
                ->take(3)
                ->get();

            foreach ($finalistes as $index => $candidat) {
                Resultat::create([
                    'annee_edition' => $anneeEdition,
                    'categorie' => $categorie,
                    'prix' => $index + 1,
                    'candidat_nom' => $candidat->display_name,
                    'candidat_photo' => $candidat->photo,
                    'nombre_votes' => $candidat->nombre_votes,
                    'note_jury' => null,
                    'note_technique' => null,
                    'note_originalite' => null,
                    'note_presence' => null,
                    'note_authenticite' => null,
                    'score_public' => null,
                    'score_final' => null,
                ]);
            }
        }

        $this->calculerScoresPublics($anneeEdition);
        $this->reclasser($anneeEdition);

        if ($avaitDesPublies) {
            Resultat::byEdition($anneeEdition)->update(['publie' => true]);
        }
    }

    // Calcule les scores publics (ovations) pour une édition : 10 pts max à partir de 2500 votes, sinon proportionnel
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

    // Réattribue les prix (1er, 2ème, 3ème...) par catégorie en fonction du score final le plus élevé.
    // Les candidats sans score final complet (null) passent après les candidats classés.
    public function reclasser(string $anneeEdition): void
    {
        DB::transaction(function () use ($anneeEdition) {
            $resultats = Resultat::byEdition($anneeEdition)->get();

            foreach ($resultats->groupBy('categorie') as $items) {
                $sorted = $items
                    ->sortBy([
                        ['score_final', 'desc'],
                        ['nombre_votes', 'desc'],
                    ])
                    ->values();

                // Passe 1 : décale tous les prix vers une plage temporaire unique
                // pour éviter les violations de la contrainte unique
                // (annee_edition, categorie, prix) lors des échanges de positions.
                $decalage = $sorted->count();
                foreach ($sorted as $r) {
                    $r->prix = (int) $r->prix + $decalage;
                    $r->save();
                }

                // Passe 2 : attribue les prix finaux (1er, 2ème, 3ème...)
                foreach ($sorted as $index => $r) {
                    $r->prix = $index + 1;
                    $r->save();
                }
            }
        });
    }
}
