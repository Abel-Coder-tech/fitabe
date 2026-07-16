<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use App\Models\Parametres;
use App\Models\Votes;
use App\Services\ResultatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        if ($now >= $fin) return 'cloture';
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
            ->withSum(['votes' => fn ($q) => $q->confirme()], 'quantite')
            ->orderByDesc('votes_sum_quantite')
            ->get();

        $fedapayKey = config('services.fedapay.public_key')
            ?: Parametres::where('cle', 'fedapay_public_key')->value('valeur');
        $afficherCompteur = Parametres::where('cle', 'afficher_compteur')->value('valeur') === '1';

        return view('public.vote.index', compact(
            'candidats', 'categories', 'voteMode', 'prixDuVote',
            'fedapayKey', 'afficherCompteur',
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
            'quantite' => 'required|integer|min:1|max:1000',
        ]);

        $montant = $prixDuVote * $validated['quantite'];

        $validated['montant'] = $montant;
        $validated['statut'] = 'en_attente';
        $validated['payment_method'] = 'fedapay';
        $validated['ip_address'] = $request->ip();

        $vote = Votes::create($validated);

        return response()->json([
            'success' => true,
            'vote_id' => $vote->id,
            'montant' => $montant,
            'quantite' => $validated['quantite'],
            'candidat_nom' => Candidats::find($validated['candidat_id'])->display_name,
        ]);
    }

    // Webhook de confirmation Fedapay (appelé par Fedapay après un paiement)
    public function webhookFedapay(Request $request)
    {
        // Récupère la clé secrète du webhook depuis la config
        $webhookSecret = config('services.fedapay.webhook_secret');

        // Vérification de la signature HMAC-SHA256 si une clé est configurée
        if ($webhookSecret) {
            $signature = $request->header('X-Fedapay-Signature');
            $rawBody = $request->getContent();
            $expected = hash_hmac('sha256', $rawBody, $webhookSecret);

            if (!$signature || !hash_equals($expected, $signature)) {
                Log::warning('Fedapay webhook : signature invalide', [
                    'expected' => $expected,
                    'received' => $signature,
                ]);
                return response()->json(['success' => false, 'message' => 'Signature invalide'], 403);
            }
        }

        // Parse le payload JSON envoyé par Fedapay
        $payload = $request->json()->all();
        $event = $payload['event'] ?? '';
        $transaction = $payload['data']['transaction'] ?? [];

        // Ne traite que les transactions approuvées
        if ($event !== 'transaction.approved' && ($transaction['status'] ?? '') !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Événement ignoré']);
        }

        // Récupère l'ID de transaction et le vote_id depuis les custom_data
        $transactionId = $transaction['id'] ?? $request->input('transaction_id');
        $voteId = $transaction['custom_data']['vote_id'] ?? $request->input('vote_id');

        if (!$voteId) {
            Log::error('Fedapay webhook : vote_id manquant', ['payload' => $payload]);
            return response()->json(['success' => false, 'message' => 'vote_id manquant'], 400);
        }

        // Confirme le vote
        $vote = Votes::find($voteId);
        if (!$vote) {
            Log::error('Fedapay webhook : vote introuvable', ['vote_id' => $voteId]);
            return response()->json(['success' => false, 'message' => 'Vote introuvable'], 404);
        }

        if ($vote->statut === 'en_attente') {
            $vote->marquerConfirme($transactionId ?: 'fedapay_' . $vote->id, 'fedapay');
            Log::info('Fedapay webhook : vote confirmé', [
                'vote_id' => $voteId,
                'transaction_id' => $transactionId,
            ]);
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

    // Page de remerciement après un vote (appelée par le callback Fedapay)
    public function merci(Request $request)
    {
        $vote = null;
        $statut = 'en_attente';

        if ($request->query('vote_id')) {
            $vote = Votes::with('candidat')->find($request->query('vote_id'));

            // Si Fedapay nous renvoie un statut "approved" dans l'URL de callback,
            // on confirme immédiatement le vote (au cas où le webhook n'est pas encore passé)
            if ($vote && $vote->statut === 'en_attente') {
                $callbackStatus = $request->query('status');
                if ($callbackStatus === 'approved') {
                    $transactionId = $request->query('transaction_id') ?: 'fedapay_cb_' . $vote->id;
                    $vote->marquerConfirme($transactionId, 'fedapay');
                }
            }

            if ($vote) {
                $statut = $vote->statut;
            }
        }

        // Requête AJAX de polling → retourne JSON
        if ($request->query('check') === '1') {
            return response()->json(['statut' => $statut]);
        }

        return view('public.vote.merci', compact('vote', 'statut'));
    }
}
