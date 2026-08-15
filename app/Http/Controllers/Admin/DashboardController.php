<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Votes;
use App\Models\VoteLog;
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
        $totalFrais = Votes::confirme()->sum('frais');
        $netRecettes = max(0, $totalRecettes - $totalFrais);

        // Taux de réussite des transactions (toutes, y compris rejetées/attente)
        $nbConfirme = Votes::confirme()->count();
        $nbRejete = Votes::rejete()->count();
        $nbEnAttente = Votes::enAttente()->count();
        $totalTransactions = $nbConfirme + $nbRejete + $nbEnAttente;
        $tauxReussite = $totalTransactions > 0 ? (int) round($nbConfirme / $totalTransactions * 100) : 0;

        // Répartition par opérateur (transactions confirmées)
        $repartitionOperateurs = Votes::confirme()
            ->select('operateur', DB::raw('COUNT(*) as nb'), DB::raw('SUM(montant) as total_montant'))
            ->whereNotNull('operateur')
            ->groupBy('operateur')
            ->orderByDesc('nb')
            ->get();
        $repartitionMax = $repartitionOperateurs->max('nb') ?: 1;

        // Alertes : votes bloqués en attente depuis > 10 min + anomalies de paiement sur 24 h
        $votesBloques = Votes::enAttente()->where('created_at', '<', now()->subMinutes(10))->count();
        $alertesPaiements = VoteLog::where('statut', 'erreur')->where('created_at', '>=', now()->subDay())->count();
        $anomaliesRecentes = VoteLog::where('statut', 'erreur')->latest()->take(5)->get();

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
            ->take(10)
            ->get();

        $messagesRecents = Contact::latest()->take(5)->get();

        $dateDebut = Parametre::debutEffectif();
        $dateFin = Parametre::finEffective() ?: Parametres::where('cle', 'date_fin_vote')->value('valeur');
        $dateDebutPlanifie = Parametres::where('cle', 'date_debut_vote')->value('valeur');

        $voteMode = \App\Support\Parametre::voteMode();
        $prixDuVote = Parametre::getInt('prix_ovation', 100);

        $logsPaiements = VoteLog::query()->latest('created_at')->take(6)->get();

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
            'totalFrais', 'netRecettes',
            'nbConfirme', 'nbRejete', 'nbEnAttente', 'tauxReussite',
            'repartitionOperateurs', 'repartitionMax',
            'votesBloques', 'alertesPaiements', 'anomaliesRecentes',
            'votesParCategorie', 'totalVotes',
            'dernieresTransactions', 'messagesRecents',
            'voteMode', 'prixDuVote', 'dateFin', 'dateDebut', 'dateDebutPlanifie',
            'logsPaiements',
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
