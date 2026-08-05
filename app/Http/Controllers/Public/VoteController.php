<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use App\Models\Parametres;
use App\Models\Resultat;
use App\Models\Votes;
use App\Models\VoteLog;
use App\Services\ResultatService;
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
        $dateFinale = Parametres::where('cle', 'date_finale')->value('valeur');

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
            'dateDebut', 'dateFin', 'dateFinale', 'finalePassee',
            'candidatPartage',
            'resultats', 'resultatsPublies'
        ));
    }

    // Soumet un vote (vérifie mode, valide, crée)
    public function store(Request $request)
    {
        $dateDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $dateFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');
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
        $data = $request->all();

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

        // Paiements non confirmants : on logue mais rien à traiter
        if (!in_array($status, ['approved', 'completed', 'accepted'], true)) {
            VoteLog::create([
                'type' => 'webhook',
                'statut' => 'ignore',
                'categorie' => 'statut_non_confirmant',
                'message' => "Statut reçu non confirmant : {$status}",
                'transaction_id' => $transactionId,
                'contexte' => json_encode(self::contexteFedapay($data), JSON_UNESCAPED_UNICODE),
            ]);

            return response()->json(['status' => 'ok']);
        }

        $voteId = $data['custom_metadata']['vote_id'] ?? $data['external_id'] ?? null;

        $vote = $voteId
            ? Votes::find($voteId)
            : Votes::where('transaction_id', $transactionId)->first();

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
            VoteLog::create([
                'type' => 'webhook',
                'statut' => 'ignore',
                'categorie' => 'deja_confirme',
                'message' => "Webhook reçu alors que le vote est déjà « {$vote->statut} ».",
                'transaction_id' => $transactionId,
                'vote_id' => $vote->id,
                'montant' => $vote->montant,
                'contexte' => json_encode(self::contexteFedapay($data), JSON_UNESCAPED_UNICODE),
            ]);

            return response()->json(['status' => 'ok']);
        }

        $telephone = $data['phone'] ?? $data['customer']['phone_number'] ?? $data['customer']['phone'] ?? null;
        $email = $data['customer']['email'] ?? null;
        $moyenPaiement = $data['payment_method'] ?? $data['payment_method']['type'] ?? null;

        $vote->marquerConfirme($transactionId, 'fedapay', $telephone, $email, $moyenPaiement);

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
        return [
            'id' => $data['id'] ?? null,
            'status' => $data['status'] ?? null,
            'amount' => $data['amount'] ?? null,
            'currency' => $data['currency'] ?? null,
            'phone' => $data['phone'] ?? $data['customer']['phone_number'] ?? $data['customer']['phone'] ?? null,
            'email' => $data['customer']['email'] ?? null,
            'payment_method' => $data['payment_method'] ?? null,
            'external_id' => $data['external_id'] ?? null,
            'created_at' => $data['created_at'] ?? null,
        ];
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

        $ancienDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $ancienFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');

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
                $transactionId = $request->query('id');
                $status = $request->query('status');
                $paymentMethod = $request->query('payment_method');
                $phone = $request->query('phone');

                if ($transactionId && in_array($status, ['approved', 'completed', 'accepted'], true)) {
                    if ($vote->statut === 'en_attente') {
                        $vote->marquerConfirme($transactionId, 'fedapay', $phone);
                        VoteLog::create([
                            'type' => 'callback',
                            'statut' => 'ok',
                            'categorie' => 'confirme_callback',
                            'message' => 'Vote confirmé via le retour Fedapay (page de remerciement).',
                            'transaction_id' => $transactionId,
                            'vote_id' => $vote->id,
                            'montant' => $vote->montant,
                        ]);
                    } else {
                        VoteLog::create([
                            'type' => 'callback',
                            'statut' => 'ignore',
                            'categorie' => 'deja_confirme',
                            'message' => "Retour Fedapay reçu alors que le vote est déjà « {$vote->statut} ».",
                            'transaction_id' => $transactionId,
                            'vote_id' => $vote->id,
                            'montant' => $vote->montant,
                        ]);
                    }
                } elseif ($transactionId) {
                    VoteLog::create([
                        'type' => 'callback',
                        'statut' => 'ignore',
                        'categorie' => 'statut_non_confirmant',
                        'message' => "Retour Fedapay avec statut non confirmant : {$status}",
                        'transaction_id' => $transactionId,
                        'vote_id' => $vote->id,
                        'montant' => $vote->montant,
                    ]);
                }

                $statut = $vote->statut;
            } else {
                // Paiement annoncé mais vote introuvable depuis la page de retour
                VoteLog::create([
                    'type' => 'callback',
                    'statut' => 'erreur',
                    'categorie' => 'vote_non_trouve',
                    'message' => 'Retour Fedapay avec vote introuvable : ovation non comptée.',
                    'transaction_id' => $request->query('id'),
                    'vote_id' => (int) $request->query('vote_id'),
                ]);
            }
        }

        if ($request->query('check') === '1') {
            return response()->json(['statut' => $statut]);
        }

        return view('public.vote.merci', compact('vote', 'statut'));
    }
}
