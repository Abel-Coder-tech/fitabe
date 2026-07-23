<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Votes;
use App\Models\Candidats;
use App\Models\Contact;
use App\Models\Parametres;
use App\Support\Parametre;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $votesConfirmes = Votes::confirme()->count();
        $messagesNonLus = Contact::nonLu()->count();
        $totalRecettes = Votes::confirme()->sum('montant');

        $votesParCategorie = Candidats::select('categorie')
            ->withCount(['votes' => fn ($q) => $q->confirme()])
            ->get()
            ->groupBy('categorie')
            ->map(fn ($items, $cat) => (object) [
                'categorie' => $cat,
                'total' => $items->sum('votes_sum_quantite'),
            ])
            ->sortByDesc('total')
            ->values();

        $candidatsAvecVotes = Candidats::query()
            ->withSum(['votes' => fn ($q) => $q->confirme()], 'quantite')
            ->orderByDesc('votes_sum_quantite')
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
            $voteMode = match (true) {
                $now < $debut => 'off',
                $now >= $fin => 'cloture',
                default => 'active',
            };
        } else {
            $voteMode = 'off';
        }
        $prixDuVote = Parametre::getInt('prix_ovation', 100);

        $totalVotes = $votesParCategorie->sum('total');
        $categories = Candidats::select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        return view('admin.dashboard.index', compact(
            'votesConfirmes', 'messagesNonLus', 'totalRecettes',
            'votesParCategorie', 'totalVotes',
            'dernieresTransactions', 'messagesRecents',
            'voteMode', 'prixDuVote', 'dateFin',
            'candidatsAvecVotes', 'categories'
        ));
    }
}
