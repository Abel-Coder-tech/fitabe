<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Medias;
use App\Models\Resultat;

class MediaController extends Controller
{
    // Page publique galerie : photos, vidéos et résultats
    public function index()
    {
        $order = ['ordre_affichage', 'asc'];
        $photos = Medias::where('type', 'photo')->orderBy(...$order)->get();
        $videos = Medias::where('type', 'video')->orderBy(...$order)->get();
        $annees = Medias::whereNotNull('annee_edition')->distinct()->orderBy('annee_edition', 'desc')->pluck('annee_edition');
        $medias = Medias::orderBy('ordre_affichage')->latest()->paginate(24);

        $photosJson = $photos->map(fn($m) => [
            'url' => $m->thumbnail,
            'titre' => $m->titre,
            'description' => $m->description,
        ]);
        $videosJson = $videos->map(fn($m) => [
            'id' => $m->youtube_id,
            'titre' => $m->titre,
            'description' => $m->description,
        ]);

        $editions = Resultat::select('annee_edition')->distinct()->orderBy('annee_edition', 'desc')->pluck('annee_edition');
        $resultats = Resultat::orderBy('annee_edition', 'desc')->orderBy('categorie')->orderBy('prix')->get()
            ->groupBy('annee_edition');

        $editionsJson = $resultats->map(fn($items, $annee) => [
            'annee' => $annee,
            'categories' => $items->groupBy('categorie')->map(fn($catItems, $cat) => [
                'categorie' => $cat,
                'resultats' => $catItems->map(fn($r) => [
                    'prix' => $r->prix,
                    'prix_label' => $r->prix_label,
                    'candidat_nom' => $r->candidat_nom,
                    'candidat_photo' => $r->candidat_photo_url,
                    'nombre_votes' => $r->nombre_votes,
                    'score_final' => $r->score_final,
                ]),
            ])->values(),
        ])->values();

        return view('public.media.index', compact(
            'medias', 'photos', 'videos', 'annees', 'photosJson', 'videosJson',
            'editions', 'editionsJson'
        ));
    }
}
