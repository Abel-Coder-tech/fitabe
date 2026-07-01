<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Medias;

class MediaController extends Controller
{
    public function index()
    {
        $medias = Medias::with('candidat')->latest()->paginate(24);
        $photos = Medias::where('type', 'photo')->latest()->get();
        $videos = Medias::where('type', 'video')->latest()->get();
        $annees = Medias::selectRaw('YEAR(created_at) as annee')->distinct()->orderBy('annee', 'desc')->pluck('annee');

        return view('public.media.index', compact('medias', 'photos', 'videos', 'annees'));
    }
}
