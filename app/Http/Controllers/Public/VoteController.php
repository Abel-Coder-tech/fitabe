<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use App\Models\Parametres;
use App\Models\Votes;
use App\Services\ResultatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class VoteController extends Controller
{
    // Injection du service de résultats
    public function __construct(
        protected ResultatService $resultatService
    ) {}

    // Helper : détermine le mode vote à partir des dates uniquement
    private function computeVoteMode($dateDebut, $dateFin)
    {
        if (!$dateDebut || !$dateFin) {
            return 'off';
        }
        $now = Carbon::now();
        $debut = Carbon::parse($dateDebut);
        $fin = Carbon::parse($dateFin);

        if ($now < $debut) return 'off';
        if ($now > $fin) return 'cloture';
        return 'active';
    }

    // Page publique de vote avec candidats et paramètres
    public function index()
    {
        $dateDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $dateFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');
        $voteMode = $this->computeVoteMode($dateDebut, $dateFin);

        $prixDuVote = 100;

        $categories = Candidats::select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        $candidats = Candidats::query()
            ->withCount(['votes' => fn ($q) => $q->confirme()])
            ->orderedByVotes()
            ->get()
            ->groupBy('categorie');

        $kkiapayKey = Parametres::where('cle', 'kkiapay_public_key')->value('valeur') ?? '';
        $fedapayKey = Parametres::where('cle', 'fedapay_public_key')->value('valeur') ?? '';
        $afficherCompteur = Parametres::where('cle', 'afficher_compteur')->value('valeur') === '1';

        return view('public.vote.index', compact(
            'candidats', 'categories', 'voteMode', 'prixDuVote',
            'kkiapayKey', 'fedapayKey', 'afficherCompteur',
            'dateDebut', 'dateFin'
        ));
    }

    // Soumet un vote (vérifie mode, valide, crée)
    public function store(Request $request)
    {
        $dateDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $dateFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');
        $voteMode = $this->computeVoteMode($dateDebut, $dateFin);
        if ($voteMode !== 'active') {
            return response()->json(['success' => false, 'message' => 'Le vote est fermé.'], 403);
        }

        $prixDuVote = 100;

        $validated = $request->validate([
            'candidat_id' => 'required|exists:candidats,id',
            'votant_nom' => 'required|string|max:255',
            'votant_email' => 'required|email|max:255',
            'votant_telephone' => 'required|string|max:50',
            'quantite' => 'required|integer|min:1|max:100',
            'payment_method' => 'required|in:kkiapay,fedapay',
        ]);

        $montant = $prixDuVote * $validated['quantite'];

        $validated['montant'] = $montant;
        $validated['statut'] = 'en_attente';
        $validated['ip_address'] = $request->ip();

        $vote = Votes::create($validated);

        return response()->json([
            'success' => true,
            'vote_id' => $vote->id,
            'montant' => $montant,
            'quantite' => $validated['quantite'],
            'candidat_nom' => Candidats::find($validated['candidat_id'])->display_name,
            'payment_method' => $validated['payment_method'],
        ]);
    }

    // Webhook de confirmation Kkiapay
    public function webhookKkiapay(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status');
        $voteId = $request->input('vote_id');

        if ($status === 'success' && $voteId) {
            $vote = Votes::find($voteId);
            if ($vote && $vote->statut === 'en_attente') {
                $vote->marquerConfirme($transactionId, 'kkiapay');
            }
        }

        return response()->json(['success' => true]);
    }

    // Webhook de confirmation Fedapay
    public function webhookFedapay(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status');
        $voteId = $request->input('vote_id');

        if ($status === 'success' && $voteId) {
            $vote = Votes::find($voteId);
            if ($vote && $vote->statut === 'en_attente') {
                $vote->marquerConfirme($transactionId, 'fedapay');
            }
        }

        return response()->json(['success' => true]);
    }

    // Met à jour les paramètres de vote et génère les résultats si clôture
    public function updateSettings(Request $request)
    {
        if ($request->user()->role !== 'super_admin') {
            abort(403);
        }

        $data = $request->validate([
            'date_debut_vote' => 'nullable|date',
            'date_fin_vote' => 'nullable|date|after_or_equal:date_debut_vote',
            'afficher_compteur' => 'nullable|in:0,1',
        ]);

        Parametres::updateOrCreate(['cle' => 'date_debut_vote'], ['valeur' => $data['date_debut_vote'] ?? '']);
        Parametres::updateOrCreate(['cle' => 'date_fin_vote'], ['valeur' => $data['date_fin_vote'] ?? '']);
        Parametres::updateOrCreate(['cle' => 'afficher_compteur'], ['valeur' => $data['afficher_compteur'] ?? '0']);

        $now = Carbon::now();
        $fin = $data['date_fin_vote'] ? Carbon::parse($data['date_fin_vote']) : null;
        if ($fin && $now > $fin) {
            $annee = $request->input('annee_resultats', date('Y'));
            $this->resultatService->generer($annee);
        }

        return response()->json(['success' => true]);
    }

    // Page de remerciement après un vote
    public function merci(Request $request)
    {
        $vote = null;
        if ($request->query('vote_id')) {
            $vote = Votes::with('candidat')->find($request->query('vote_id'));
        }

        return view('public.vote.merci', compact('vote'));
    }
}
