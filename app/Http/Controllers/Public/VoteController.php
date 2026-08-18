<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use App\Models\Parametres;
use App\Models\Resultat;
use App\Models\Votes;
use App\Models\VoteLog;
use App\Services\ResultatService;
use App\Support\FedaPayInfos;
use App\Support\Parametre;
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

    // Helper : détermine le mode vote (statut manuel prioritaire, sinon dates) — voir Parametre::voteMode()

    // Page publique de vote avec candidats et paramètres
    public function index(Request $request)
    {
        // Fenêtre effective : si l'admin a démarré manuellement, c'est cette heure qui fait foi
        $dateDebut = Parametre::debutEffectif();
        $dateFin = Parametre::finEffective();
        $dateFinale = Parametre::get('date_finale');

        $voteMode = Parametre::voteMode();

        $finalePassee = $dateFinale && Carbon::now()->gte(Carbon::parse($dateFinale));

        $prixDuVote = Parametre::getInt('prix_ovation', 100);

        $categories = Candidats::select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        $candidats = Candidats::query()
            ->withSum(['votes' => fn ($q) => $q->confirme()], 'quantite')
            ->orderByDesc('votes_sum_quantite')
            ->get();

        $fedapayKey = config('services.fedapay.public_key')
            ?: Parametre::get('fedapay_public_key');
        $fedapayMode = config('services.fedapay.mode', 'live');
        $afficherCompteur = Parametre::get('afficher_compteur') === '1';

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
            'dateDebut', 'dateFin', 'dateFinale', 'finalePassee',
            'candidatPartage',
            'resultats', 'resultatsPublies'
        ));
    }

    // Soumet un vote (vérifie mode, valide, crée)
    public function store(Request $request)
    {
        $dateDebut = Parametre::get('date_debut_vote');
        $dateFin = Parametre::get('date_fin_vote');
        $voteMode = Parametre::voteMode();
        if ($voteMode !== 'active') {
            return response()->json(['success' => false, 'message' => 'Le vote est fermé.'], 403);
        }

        $prixDuVote = Parametre::getInt('prix_ovation', 100);

        // Normalise « 01 » -> « 1 » pour que la règle « integer » l'accepte
        $request->merge([
            'quantite' => preg_replace('/^0+(?=\d)/', '', (string) $request->input('quantite')),
        ]);

        $validated = $request->validate([
            'candidat_id' => 'required|exists:candidates,id',
            'quantite' => 'required|integer|min:1',
        ], [
            'candidat_id.required' => 'Veuillez sélectionner un candidat.',
            'candidat_id.exists' => 'Ce candidat n\'existe pas.',
            'quantite.required' => 'Veuillez indiquer un nombre d\'ovations.',
            'quantite.integer' => 'Le nombre d\'ovations doit être un nombre entier.',
            'quantite.min' => 'Le nombre d\'ovations doit être d\'au moins 1.',
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
        $payload = (string) $request->getContent();
        $signature = $request->header('X-FEDAPAY-SIGNATURE');

        if (! $this->verifierSignatureFedapay($payload, $signature)) {
            VoteLog::create([
                'type' => 'webhook',
                'statut' => 'erreur',
                'categorie' => 'signature_invalide',
                'message' => 'Webhook FedaPay rejeté : signature absente ou invalide.',
                'contexte' => json_encode(['header_recu' => $signature], JSON_UNESCAPED_UNICODE),
            ]);

            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = json_decode($payload, true) ?? [];

        if (!isset($data['id']) || !isset($data['status'])) {
            VoteLog::create([
                'type' => 'webhook',
                'statut' => 'erreur',
                'categorie' => 'payload_incomplet',
                'message' => 'Webhook reçu sans id ou status.',
                'contexte' => json_encode(['payload' => $data], JSON_UNESCAPED_UNICODE),
            ]);

            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $transactionId = (string) $data['id'];
        $status = (string) $data['status'];

        $voteId = $data['custom_metadata']['vote_id'] ?? $data['external_id'] ?? null;

        $vote = $voteId
            ? Votes::find($voteId)
            : Votes::where('transaction_id', $transactionId)->first();

        $statutsConfirmants = ['approved', 'completed', 'accepted'];
        $statutsEchec = ['declined', 'canceled', 'refunded', 'expired', 'rejected', 'failed'];

        // Paiements non confirmants : un échec définitif rejette l'ovation (pour un
        // taux de réussite fiable), les autres statuts sont simplement journalisés.
        if (!in_array($status, $statutsConfirmants, true)) {
            if (in_array($status, $statutsEchec, true) && $vote && $vote->statut === 'en_attente') {
                $vote->marquerRejete($transactionId);

                VoteLog::create([
                    'type' => 'webhook',
                    'statut' => 'ok',
                    'categorie' => 'rejete',
                    'message' => "Paiement {$status} : ovation non comptée.",
                    'transaction_id' => $transactionId,
                    'vote_id' => $vote->id,
                    'montant' => $vote->montant,
                    'contexte' => json_encode(self::contexteFedapay($data), JSON_UNESCAPED_UNICODE),
                ]);

                Log::info('Fedapay webhook : vote rejeté', ['vote_id' => $vote->id, 'transaction_id' => $transactionId, 'status' => $status]);
            } else {
                VoteLog::create([
                    'type' => 'webhook',
                    'statut' => 'ignore',
                    'categorie' => 'statut_non_confirmant',
                    'message' => "Statut reçu non confirmant : {$status}",
                    'transaction_id' => $transactionId,
                    'contexte' => json_encode(self::contexteFedapay($data), JSON_UNESCAPED_UNICODE),
                ]);
            }

            return response()->json(['status' => 'ok']);
        }

        if (!$vote) {
            // Paiement approuvé mais vote introuvable → ovation non comptée
            VoteLog::create([
                'type' => 'webhook',
                'statut' => 'erreur',
                'categorie' => 'vote_non_trouve',
                'message' => 'Paiement approuvé mais vote introuvable : ovation non comptée.',
                'transaction_id' => $transactionId,
                'vote_id' => $voteId ? (int) $voteId : null,
                'contexte' => json_encode(self::contexteFedapay($data), JSON_UNESCAPED_UNICODE),
            ]);

            Log::warning('Fedapay webhook : vote introuvable', ['transaction_id' => $transactionId, 'vote_id' => $voteId]);

            return response()->json(['status' => 'ok']);
        }

        if ($vote->statut !== 'en_attente') {
            return response()->json(['status' => 'ok']);
        }

        $telephone = $data['phone'] ?? $data['customer']['phone_number'] ?? $data['customer']['phone'] ?? null;
        $email = $data['customer']['email'] ?? null;
        $rawMoyenPaiement = $data['payment_method'] ?? null;
        $moyenPaiement = is_string($rawMoyenPaiement)
            ? $rawMoyenPaiement
            : (is_array($rawMoyenPaiement) ? ($rawMoyenPaiement['type'] ?? null) : null);

        // Opérateur (MTN/Moov/Celtiis/Carte), pays et frais à partir du webhook
        $mode = is_array($rawMoyenPaiement)
            ? ($rawMoyenPaiement['mode'] ?? $rawMoyenPaiement['type'] ?? null)
            : ($rawMoyenPaiement ?? $data['mode'] ?? null);
        $mode = is_string($mode) ? $mode : null;

        $operateur = FedaPayInfos::operateur($mode);
        $pays = FedaPayInfos::pays($data['customer']['country'] ?? null, $telephone);
        $frais = FedaPayInfos::frais($data);

        // Vérifie que le montant payé correspond au montant attendu du vote
        $montantPaye = $data['amount'] ?? null;
        if ($montantPaye !== null && (int) $montantPaye !== (int) $vote->montant) {
            VoteLog::create([
                'type' => 'webhook',
                'statut' => 'erreur',
                'categorie' => 'montant_incoherent',
                'message' => 'Montant payé différent du montant attendu : ovation non comptée.',
                'transaction_id' => $transactionId,
                'vote_id' => $vote->id,
                'montant' => $vote->montant,
                'contexte' => json_encode(['montant_paye' => $montantPaye] + self::contexteFedapay($data), JSON_UNESCAPED_UNICODE),
            ]);

            return response()->json(['status' => 'ok']);
        }

        $vote->marquerConfirme($transactionId, 'fedapay', $telephone, $email, $moyenPaiement, $frais, $operateur, $pays);

        VoteLog::create([
            'type' => 'webhook',
            'statut' => 'ok',
            'categorie' => 'confirme',
            'message' => 'Vote confirmé via webhook Fedapay.',
            'transaction_id' => $transactionId,
            'vote_id' => $vote->id,
            'montant' => $vote->montant,
            'contexte' => json_encode(self::contexteFedapay($data), JSON_UNESCAPED_UNICODE),
        ]);

        Log::info('Fedapay webhook : vote confirmé', [
            'vote_id' => $vote->id,
            'transaction_id' => $transactionId,
        ]);

        return response()->json(['status' => 'ok']);
    }

    private static function contexteFedapay(array $data): array
    {
        $rawPm = $data['payment_method'] ?? null;

        return [
            'id' => $data['id'] ?? null,
            'status' => $data['status'] ?? null,
            'amount' => $data['amount'] ?? null,
            'currency' => $data['currency'] ?? null,
            'fees' => $data['fees'] ?? null,
            'mode' => is_array($rawPm) ? ($rawPm['mode'] ?? null) : ($data['mode'] ?? $rawPm),
            'payment_method' => is_string($rawPm) ? $rawPm : ($rawPm['type'] ?? null),
            'phone' => $data['phone'] ?? $data['customer']['phone_number'] ?? $data['customer']['phone'] ?? null,
            'email' => $data['customer']['email'] ?? null,
            'country' => $data['customer']['country'] ?? null,
            'external_id' => $data['external_id'] ?? null,
            'created_at' => $data['created_at'] ?? null,
        ];
    }

    // Vérifie la signature HMAC-SHA256 envoyée par FedaPay (en-tête X-FEDAPAY-SIGNATURE).
    // Format : t=<timestamp>,s=<hex hmac-sha256("$timestamp.$payload", webhook_secret)>
    private function verifierSignatureFedapay(string $payload, ?string $header): bool
    {
        $secret = config('services.fedapay.webhook_secret');

        if (! $secret || ! $header) {
            return false;
        }

        $timestamp = null;
        $signature = null;

        foreach (explode(',', $header) as $item) {
            $parts = explode('=', $item, 2);
            if (($parts[0] ?? '') === 't' && isset($parts[1]) && is_numeric($parts[1])) {
                $timestamp = (int) $parts[1];
            } elseif (($parts[0] ?? '') === 's') {
                $signature = $parts[1] ?? null;
            }
        }

        if (! $timestamp || ! $signature) {
            return false;
        }

        // Tolérance anti-rejeu : la signature ne doit pas dater de plus de 5 minutes
        if (abs(time() - $timestamp) > 300) {
            return false;
        }

        $expected = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

        return hash_equals($expected, $signature);
    }

    // Met à jour les paramètres de vote et génère les résultats si clôture
    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'date_debut_vote' => 'nullable|date',
            'date_fin_vote' => 'nullable|date|after_or_equal:date_debut_vote',
            'date_finale' => 'nullable|date|after_or_equal:date_fin_vote',
            'afficher_compteur' => 'nullable|in:0,1',
            'annee_resultats' => 'nullable|string|max:4',
        ], [
            'date_debut_vote.date' => 'La date de début doit être une date valide.',
            'date_fin_vote.date' => 'La date de fin doit être une date valide.',
            'date_fin_vote.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'date_finale.date' => 'La date de la Grande Finale doit être une date valide.',
            'date_finale.after_or_equal' => 'La date de la Grande Finale doit être postérieure ou égale à la date de fin.',
            'afficher_compteur.in' => 'La valeur du compteur est invalide.',
            'annee_resultats.max' => 'L\'année des résultats doit contenir au maximum 4 caractères.',
        ]);

        $ancienDebut = Parametre::get('date_debut_vote');
        $ancienFin = Parametre::get('date_fin_vote');

        Parametres::updateOrCreate(['cle' => 'date_debut_vote'], ['valeur' => $data['date_debut_vote'] ?? '']);
        Parametres::updateOrCreate(['cle' => 'date_fin_vote'], ['valeur' => $data['date_fin_vote'] ?? '']);
        Parametres::updateOrCreate(['cle' => 'date_finale'], ['valeur' => $data['date_finale'] ?? '']);
        Parametres::updateOrCreate(['cle' => 'afficher_compteur'], ['valeur' => $data['afficher_compteur'] ?? '0']);

        // Si les dates planifiées changent, le démarrage manuel éventuel est réinitialisé :
        // la nouvelle planification reprend la main (mode automatique)
        if (($data['date_debut_vote'] ?? '') !== ($ancienDebut ?? '') || ($data['date_fin_vote'] ?? '') !== ($ancienFin ?? '')) {
            Parametres::updateOrCreate(['cle' => 'debut_effectif'], ['valeur' => '']);
            Parametres::updateOrCreate(['cle' => 'statut_vote'], ['valeur' => '']);
        }

        Parametre::flush();

        $now = Carbon::now();
        $debut = $data['date_debut_vote'] ? Carbon::parse($data['date_debut_vote']) : null;
        $fin = $data['date_fin_vote'] ? Carbon::parse($data['date_fin_vote']) : null;

        // Génère les résultats si la période de vote est déjà passée
        if ($fin && $now > $fin) {
            $annee = $request->input('annee_resultats', date('Y'));
            $this->resultatService->generer($annee);
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
                $statut = $vote->statut;
            }
        }

        if ($request->query('check') === '1') {
            return response()->json(['statut' => $statut]);
        }

        return view('public.vote.merci', compact('vote', 'statut'));
    }
}
