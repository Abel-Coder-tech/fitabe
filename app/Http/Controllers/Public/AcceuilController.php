<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Programmes;
use App\Models\Partenaires;
use Carbon\Carbon;

class AcceuilController extends Controller
{
    public function index()
    {
        Carbon::setLocale('fr');

        $programmes = Programmes::actif()->ordered()->get();
        $jours = $programmes->groupBy(fn($e) => $e->date_programme->format('Y-m-d'));

        $partenaires = Partenaires::ordered()->get();

        return view('public.home', compact('jours', 'partenaires'));
    }
}
