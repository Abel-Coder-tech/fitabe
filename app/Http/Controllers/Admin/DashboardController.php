<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Votes;
use App\Models\Candidats;
use App\Models\Contact;
use App\Models\Parametres;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $votesConfirmes = Votes::confirme()->count();
        $messagesNonLus = Contact::nonLu()->count();
        $totalRecettes = Votes::confirme()->sum('montant');

        $votesParCategorie = Candidats::select('categorie', DB::raw('sum(nombre_votes) as total'))
            ->groupBy('categorie')
            ->orderByDesc('total')
            ->get();

        $dernieresTransactions = Votes::with('candidat')
            ->confirme()
            ->latest()
            ->take(10)
            ->get();

        $messagesRecents = Contact::latest()->take(5)->get();

        $voteMode = Parametres::where('cle', 'vote_mode')->value('valeur') ?? 'off';
        $prixDuVote = Parametres::where('cle', 'prix_du_vote')->value('valeur') ?? '100';
        $voteDeadline = Parametres::where('cle', 'vote_deadline')->value('valeur');

        $totalVotes = $votesParCategorie->sum('total');

        return view('admin.dashboard.index', compact(
            'votesConfirmes', 'messagesNonLus', 'totalRecettes',
            'votesParCategorie', 'totalVotes',
            'dernieresTransactions', 'messagesRecents',
            'voteMode', 'prixDuVote', 'voteDeadline'
        ));
    }
}
