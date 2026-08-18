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
        // 1 seule requête pour toutes les stats de votes
        $stats = Votes::selectRaw('
            SUM(CASE WHEN statut = "confirme" THEN quantite ELSE 0 END) as confirmes_quantite,
            SUM(CASE WHEN statut = "confirme" THEN montant ELSE 0 END) as confirmes_montant,
            SUM(CASE WHEN statut = "confirme" THEN frais ELSE 0 END) as confirmes_frais,
            COUNT(CASE WHEN statut = "confirme" THEN 1 END) as nb_confirme,
            COUNT(CASE WHEN statut = "rejete" THEN 1 END) as nb_rejete,
            COUNT(CASE WHEN statut = "en_attente" THEN 1 END) as nb_attente
        ')->first();

        $votesConfirmes = (int) $stats->confirmes_quantite;
        $totalRecettes = (float) $stats->confirmes_montant;
        $totalFrais = (float) $stats->confirmes_frais;
        $netRecettes = max(0, $totalRecettes - $totalFrais);
        $nbConfirme = (int) $stats->nb_confirme;
        $nbRejete = (int) $stats->nb_rejete;
        $nbEnAttente = (int) $stats->nb_attente;
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

        // Votes non finalisés : en_attente avec un vrai ID FedaPay (pas pending_xxx) + anomalies de paiement sur 24 h
        $votesBloques = Votes::enAttente()
            ->where('transaction_id', 'not like', 'pending_%')
            ->count();
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
        $dateFin = Parametre::finEffective() ?: Parametre::get('date_fin_vote');
        $dateDebutPlanifie = Parametre::get('date_debut_vote');

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

        $nonLuCount = \App\Models\Contact::nonLu()->count();

        return view('admin.dashboard.index', compact(
            'votesConfirmes', 'totalRecettes',
            'totalFrais', 'netRecettes',
            'nbConfirme', 'nbRejete', 'nbEnAttente', 'tauxReussite',
            'repartitionOperateurs', 'repartitionMax',
            'votesBloques', 'alertesPaiements', 'anomaliesRecentes',
            'votesParCategorie', 'totalVotes',
            'dernieresTransactions', 'messagesRecents',
            'voteMode', 'prixDuVote', 'dateFin', 'dateDebut', 'dateDebutPlanifie',
            'logsPaiements',
            'candidatsAvecVotes', 'categories', 'categorieFiltre',
            'nonLuCount'
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
