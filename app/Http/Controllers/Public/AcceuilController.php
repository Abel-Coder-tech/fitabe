<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Partenaires;
use App\Models\Programmes;
use App\Models\Soutien;

class AcceuilController extends Controller
{
    public function index()
    {
        $partenaires = Partenaires::ordered()->get();
        $soutiens = Soutien::ordered()->get();
        $programmes = Programmes::actif()->ordered()->with('dates')->get();

        return view('public.home', compact('partenaires', 'soutiens', 'programmes'));
    }
}
