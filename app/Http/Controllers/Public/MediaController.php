<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Medias;
use App\Models\Resultat;
use Illuminate\Pagination\LengthAwarePaginator;

class MediaController extends Controller
{
    // Page publique galerie : photos, vidéos et résultats
    public function index()
    {
        $tous = Medias::orderBy('id')->get();
        $photos = $tous->where('type', 'photo')->values();
        $videos = $tous->where('type', 'video')->values();
        $annees = $tous->pluck('annee_edition')->filter()->unique()->sortDesc()->values();
        $page = (int) request()->get('page', 1);
        $perPage = 24;
        $medias = new LengthAwarePaginator(
            $tous->forPage($page, $perPage),
            $tous->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );

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

        $editions = Resultat::where('publie', true)->select('annee_edition')->distinct()->orderBy('annee_edition', 'desc')->pluck('annee_edition');
        $resultats = Resultat::where('publie', true)->orderBy('annee_edition', 'desc')->orderBy('categorie')->orderBy('prix')->get()
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
                    'note_jury' => $r->note_jury,
                    'score_public' => $r->score_public,
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
