<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Votes;
use App\Models\Candidats;
use App\Models\Contact;
use App\Models\Parametres;
use App\Support\Parametre;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $votesConfirmes = Votes::confirme()->sum('quantite');
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

        $categorieFiltre = trim((string) request('categorie', 'all'));

        $candidatsAvecVotes = Candidats::query()
            ->withSum(['votes' => fn ($q) => $q->confirme()], 'quantite')
            ->orderByDesc('votes_sum_quantite')
            ->when($categorieFiltre === '' || $categorieFiltre === 'all', fn ($q) => $q->limit(10))
            ->get()
            ->each(function ($candidat) {
                $candidat->categorie_clef = static::categorieClef($candidat->categorie);
            });

        // Filtre serveur par catégorie (clés normalisées, insensible à la casse/aux séparateurs)
        if ($categorieFiltre !== '' && $categorieFiltre !== 'all') {
            $candidatsAvecVotes = $candidatsAvecVotes
                ->filter(fn ($candidat) => $candidat->categorie_clef === $categorieFiltre)
                ->take(10)
                ->values();
        }

        $dernieresTransactions = Votes::with('candidat')
            ->confirme()
            ->latest()
            ->paginate(request()->integer('per_page', 10));

        $messagesRecents = Contact::latest()->take(5)->get();

        $dateDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $dateFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');

        $voteMode = \App\Support\Parametre::voteMode();
        $prixDuVote = Parametre::getInt('prix_ovation', 100);

        $totalVotes = $votesParCategorie->sum('total');
        $categories = Candidats::query()
            ->select('categorie')
            ->distinct()
            ->pluck('categorie')
            ->filter(fn ($cat) => ! empty($cat))
            ->map(fn ($cat) => (object) [
                'nom' => $cat,
                'clef' => static::categorieClef($cat),
            ])
            ->unique('clef')
            ->sortBy('nom')
            ->values();

        return view('admin.dashboard.index', compact(
            'votesConfirmes', 'messagesNonLus', 'totalRecettes',
            'votesParCategorie', 'totalVotes',
            'dernieresTransactions', 'messagesRecents',
            'voteMode', 'prixDuVote', 'dateFin',
            'candidatsAvecVotes', 'categories', 'categorieFiltre'
        ));
    }

    /**
     * Normalise le nom d'une catégorie en une clé stable, insensible à la
     * casse, aux espaces, accents et séparateurs (ex. « Stylisme / Modélisme »
     * et « stylisme/modélisme » donnent tous deux « stylismemodelisme »).
     */
    private static function categorieClef(?string $categorie): string
    {
        return strtolower(preg_replace('/[^a-z0-9]+/i', '', Str::ascii((string) $categorie)));
    }
}
