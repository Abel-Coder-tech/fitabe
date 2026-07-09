<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Votes;
use App\Models\Candidats;
use App\Models\Contact;
use App\Models\Parametres;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $dateDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $dateFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');
        if ($dateDebut && $dateFin) {
            $now = Carbon::now();
            $debut = Carbon::parse($dateDebut);
            $fin = Carbon::parse($dateFin);
            if ($now < $debut) $voteMode = 'off';
            elseif ($now > $fin) $voteMode = 'cloture';
            else $voteMode = 'active';
        } else {
            $voteMode = 'off';
        }
        $prixDuVote = 100;

        $totalVotes = $votesParCategorie->sum('total');

        return view('admin.dashboard.index', compact(
            'votesConfirmes', 'messagesNonLus', 'totalRecettes',
            'votesParCategorie', 'totalVotes',
            'dernieresTransactions', 'messagesRecents',
            'voteMode', 'prixDuVote', 'dateFin'
        ));
    }
}
