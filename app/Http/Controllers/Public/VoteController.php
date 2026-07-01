<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidats;

class VoteController extends Controller
{
    public function index()
    {
        $candidats = Candidats::query()
            ->with(['votes' => fn ($q) => $q->confirme()])
            ->orderedByVotes()
            ->get()
            ->groupBy('categorie');

        return view('public.vote.index', compact('candidats'));
    }
}
