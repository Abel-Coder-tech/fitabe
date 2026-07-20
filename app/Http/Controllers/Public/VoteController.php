<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use App\Models\Parametres;
use App\Models\Votes;
use App\Services\ResultatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
        $afficherCompteur = Parametres::where('cle', 'afficher_compteur')->value('valeur') === '1';

        $candidatPartage = null;
        if ($request->filled('candidat')) {
            $candidatPartage = Candidats::find($request->integer('candidat'));
        }

        return view('public.vote.index', compact(
            'candidats', 'categories', 'voteMode', 'prixDuVote',
            'fedapayKey', 'afficherCompteur',
            'dateDebut', 'dateFin', 'candidatPartage'
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

        $checkoutUrl = $this->creerCheckoutFedapay($vote, $montant);

        return response()->json([
            'success' => true,
            'vote_id' => $vote->id,
            'montant' => $montant,
            'quantite' => $validated['quantite'],
            'candidat_nom' => Candidats::find($validated['candidate_id'])->display_name,
            'checkout_url' => $checkoutUrl,
        ]);
    }

    private function creerCheckoutFedapay(Votes $vote, int $montant): string
    {
        $secretKey = config('services.fedapay.secret_key')
            ?: Parametres::where('cle', 'fedapay_secret_key')->value('valeur');
        $isLive = config('services.fedapay.mode', 'live') !== 'sandbox';
        $base = $isLive ? 'https://api.fedapay.com' : 'https://sandbox-api.fedapay.com';

        if (!$secretKey) {
            Log::warning('Fedapay : clé secrète manquante, fallback sur redirect sans création');
            return $base . '/v1/checkout?public_key=' . config('services.fedapay.public_key')
                . '&amount=' . $montant
                . '&currency=XOF'
                . '&description=Ovation+FITAB+%23' . $vote->id
                . '&callback_url=' . urlencode(route('public.vote.merci', ['vote_id' => $vote->id]))
                . '&data[vote_id]=' . $vote->id;
        }

        try {
            $response = Http::withToken($secretKey)
                ->post($base . '/v1/transactions', [
                    'amount' => $montant,
                    'currency' => ['iso' => 'XOF'],
                    'description' => 'Ovation FITAB #' . $vote->id,
                    'callback_url' => route('public.vote.merci', ['vote_id' => $vote->id]),
                    'custom_metadata' => ['vote_id' => $vote->id],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['transaction']['checkout_url'] ?? $data['checkout_url']
                    ?? $base . '/v1/checkout?public_key=' . config('services.fedapay.public_key')
                    . '&amount=' . $montant
                    . '&currency=XOF'
                    . '&description=Ovation+FITAB+%23' . $vote->id
                    . '&callback_url=' . urlencode(route('public.vote.merci', ['vote_id' => $vote->id]))
                    . '&data[vote_id]=' . $vote->id;
            }

            Log::error('Fedapay : échec création transaction', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Fedapay : exception création transaction: ' . $e->getMessage());
        }

        return $base . '/v1/checkout?public_key=' . config('services.fedapay.public_key')
            . '&amount=' . $montant
            . '&currency=XOF'
            . '&description=Ovation+FITAB+%23' . $vote->id
            . '&callback_url=' . urlencode(route('public.vote.merci', ['vote_id' => $vote->id]))
            . '&data[vote_id]=' . $vote->id;
    }

    // Webhook de confirmation Fedapay (appelé par Fedapay après un paiement)
    public function webhookFedapay(Request $request)
    {
        // Vérification obligatoire de la signature HMAC-SHA256
        $webhookSecret = config('services.fedapay.webhook_secret');
        if (!$webhookSecret) {
            Log::critical('Fedapay webhook : FEDAPAY_WEBHOOK_SECRET non configuré');
            return response()->json(['success' => false, 'message' => 'Configuration webhook manquante'], 500);
        }

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

        $telephone = $transaction['customer']['phone_number']
            ?? $transaction['customer']['phone']
            ?? $transaction['phone_number']
            ?? null;
        $email = $transaction['customer']['email'] ?? null;
        $moyenPaiement = $transaction['payment_method']['type']
            ?? $transaction['payment_method_type']
            ?? null;

        if ($vote->statut === 'en_attente') {
            $vote->marquerConfirme(
                $transactionId ?: 'fedapay_' . $vote->id,
                'fedapay',
                $telephone,
                $email,
                $moyenPaiement
            );
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

    // Page de remerciement après un vote
    public function merci(Request $request)
    {
        $vote = null;
        $statut = 'en_attente';

        if ($request->query('vote_id')) {
            $vote = Votes::with('candidat')->find($request->query('vote_id'));

            // Ne JAMAIS confirmer un vote depuis les paramètres GET.
            // Seul le webhook Fedapay peut confirmer un paiement.
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
