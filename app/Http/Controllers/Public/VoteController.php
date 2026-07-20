<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use App\Models\Parametres;
use App\Models\Resultat;
use App\Models\Votes;
use App\Services\ResultatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        if ($now >= $fin) return 'cloture';
        return 'active';
    }

    // Page publique de vote avec candidats et paramètres
    public function index(Request $request)
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
            ->withSum(['votes' => fn ($q) => $q->confirme()], 'quantite')
            ->orderByDesc('votes_sum_quantite')
            ->get();

        $fedapayKey = config('services.fedapay.public_key')
            ?: Parametres::where('cle', 'fedapay_public_key')->value('valeur');
        $fedapayMode = config('services.fedapay.mode', 'live');
        $afficherCompteur = Parametres::where('cle', 'afficher_compteur')->value('valeur') === '1';

        $resultats = collect();
        $resultatsPublies = false;
        if ($voteMode === 'cloture') {
            $anneeCourante = date('Y');
            $resultats = Resultat::where('annee_edition', $anneeCourante)->where('publie', true)
                ->orderBy('categorie')->orderBy('prix')->get()->groupBy('categorie');
            $resultatsPublies = $resultats->isNotEmpty();
        }

        $candidatPartage = null;
        if ($request->filled('candidat')) {
            $candidatPartage = Candidats::find($request->integer('candidat'));
        }

        return view('public.vote.index', compact(
            'candidats', 'categories', 'voteMode', 'prixDuVote',
            'fedapayKey', 'fedapayMode', 'afficherCompteur',
            'dateDebut', 'dateFin', 'candidatPartage',
            'resultats', 'resultatsPublies'
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
            'candidat_id' => 'required|exists:candidates,id',
            'quantite' => 'required|integer|min:1|max:1000',
        ]);

        $validated['candidate_id'] = $validated['candidat_id'];
        unset($validated['candidat_id']);

        $montant = $prixDuVote * $validated['quantite'];

        $validated['montant'] = $montant;
        $validated['statut'] = 'en_attente';
        $validated['payment_method'] = 'fedapay';
        $validated['transaction_id'] = 'pending_' . Str::uuid();
        $validated['adresse_ip'] = $request->ip();

        $vote = Votes::create($validated);

        return response()->json([
            'success' => true,
            'vote_id' => $vote->id,
            'montant' => $montant,
            'quantite' => $validated['quantite'],
            'candidat_nom' => Candidats::find($validated['candidate_id'])->display_name,
        ]);
    }

    // Webhook de confirmation Fedapay (appelé par Fedapay après un paiement)
    public function webhookFedapay(Request $request)
    {
        $data = $request->all();

        if (!isset($data['id']) || !isset($data['status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        if (in_array($data['status'], ['approved', 'completed', 'accepted'], true)) {
            $transactionId = $data['id'];
            $voteId = $data['custom_metadata']['vote_id'] ?? $data['external_id'] ?? null;

            $vote = $voteId
                ? Votes::find($voteId)
                : Votes::where('transaction_id', $transactionId)->first();

            if ($vote && $vote->statut === 'en_attente') {
                $telephone = $data['phone'] ?? $data['customer']['phone_number'] ?? $data['customer']['phone'] ?? null;
                $email = $data['customer']['email'] ?? null;
                $moyenPaiement = $data['payment_method'] ?? $data['payment_method']['type'] ?? null;

                $vote->marquerConfirme($transactionId, 'fedapay', $telephone, $email, $moyenPaiement);

                Log::info('Fedapay webhook : vote confirmé', [
                    'vote_id' => $vote->id,
                    'transaction_id' => $transactionId,
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // Met à jour les paramètres de vote et génère les résultats si clôture
    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'date_debut_vote' => 'nullable|date',
            'date_fin_vote' => 'nullable|date|after_or_equal:date_debut_vote',
            'afficher_compteur' => 'nullable|in:0,1',
            'annee_resultats' => 'nullable|string|max:4',
        ]);

        Parametres::updateOrCreate(['cle' => 'date_debut_vote'], ['valeur' => $data['date_debut_vote'] ?? '']);
        Parametres::updateOrCreate(['cle' => 'date_fin_vote'], ['valeur' => $data['date_fin_vote'] ?? '']);
        Parametres::updateOrCreate(['cle' => 'afficher_compteur'], ['valeur' => $data['afficher_compteur'] ?? '0']);

        $now = Carbon::now();
        $debut = $data['date_debut_vote'] ? Carbon::parse($data['date_debut_vote']) : null;
        $fin = $data['date_fin_vote'] ? Carbon::parse($data['date_fin_vote']) : null;

        if ($fin && $now > $fin) {
            Parametres::updateOrCreate(['cle' => 'statut_vote'], ['valeur' => 'cloture']);
            $annee = $request->input('annee_resultats', date('Y'));
            $this->resultatService->generer($annee);
        } elseif ($debut && $now >= $debut && $fin && $now < $fin) {
            Parametres::updateOrCreate(['cle' => 'statut_vote'], ['valeur' => 'active']);
        } else {
            Parametres::updateOrCreate(['cle' => 'statut_vote'], ['valeur' => 'off']);
        }

        return response()->json(['success' => true]);
    }

    // Page de remerciement après un vote (callback Fedapay)
    public function merci(Request $request)
    {
        $vote = null;
        $statut = 'en_attente';

        if ($request->query('vote_id')) {
            $vote = Votes::with('candidat')->find($request->query('vote_id'));

            if ($vote) {
                $transactionId = $request->query('id');
                $status = $request->query('status');
                $paymentMethod = $request->query('payment_method');
                $phone = $request->query('phone');

                if ($transactionId && in_array($status, ['approved', 'completed', 'accepted'], true) && $vote->statut === 'en_attente') {
                    $vote->marquerConfirme($transactionId, 'fedapay', $phone);
                }

                $statut = $vote->statut;
            }
        }

        if ($request->query('check') === '1') {
            return response()->json(['statut' => $statut]);
        }

        return view('public.vote.merci', compact('vote', 'statut'));
    }
}
