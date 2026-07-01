<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use App\Models\Parametres;
use App\Models\Votes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoteController extends Controller
{
    public function index()
    {
        $voteMode = Parametres::where('cle', 'vote_mode')->value('valeur') ?? 'off';
        $prixDuVote = (int) (Parametres::where('cle', 'prix_du_vote')->value('valeur') ?? 100);
        $voteDeadline = Parametres::where('cle', 'vote_deadline')->value('valeur');
        $afficherCompteur = Parametres::where('cle', 'afficher_compteur')->value('valeur') === '1';

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

        return view('public.vote.index', compact(
            'candidats', 'categories', 'voteMode', 'prixDuVote', 'voteDeadline', 'afficherCompteur',
            'kkiapayKey', 'fedapayKey'
        ));
    }

    public function store(Request $request)
    {
        $prixDuVote = (int) (Parametres::where('cle', 'prix_du_vote')->value('valeur') ?? 100);

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

    public function merci(Request $request)
    {
        $vote = null;
        if ($request->query('vote_id')) {
            $vote = Votes::with('candidat')->find($request->query('vote_id'));
        }

        return view('public.vote.merci', compact('vote'));
    }
}
