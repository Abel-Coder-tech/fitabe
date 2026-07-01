<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Medias;

class MediaController extends Controller
{
    public function index()
    {
        $medias = Medias::with('candidat')->latest()->get();

        return view('public.media.index', compact('medias'));
    }
}
