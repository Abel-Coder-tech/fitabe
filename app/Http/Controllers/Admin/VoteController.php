<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametres;
use App\Models\Votes;
use App\Models\Candidats;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    // Affiche la liste des votes avec les paramètres
    public function index()
    {
        $votes = Votes::with('candidat')->latest()->paginate(20);

        $voteMode = Parametres::where('cle', 'vote_mode')->value('valeur') ?? 'off';
        $prixDuVote = (int) (Parametres::where('cle', 'prix_du_vote')->value('valeur') ?? 100);
        $voteDeadline = Parametres::where('cle', 'vote_deadline')->value('valeur');
        $afficherCompteur = Parametres::where('cle', 'afficher_compteur')->value('valeur') === '1';

        return view('admin.votes.index', compact(
            'votes', 'voteMode', 'prixDuVote', 'voteDeadline', 'afficherCompteur'
        ));
    }

    // Affiche le détail d'un vote
    public function show(Votes $vote)
    {
        $vote->load('candidat');
        return view('admin.votes.show', compact('vote'));
    }

    // Met à jour le statut d'un vote et ajuste le compteur
    public function update(Request $request, Votes $vote)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente,confirme,rejete',
        ], [
            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut sélectionné est invalide.',
        ]);

        $ancien_statut = $vote->statut;
        $vote->update($validated);

        if ($ancien_statut === 'confirme' && $validated['statut'] !== 'confirme') {
            $vote->candidat->decrement('nombre_votes');
        } elseif ($ancien_statut !== 'confirme' && $validated['statut'] === 'confirme') {
            $vote->candidat->increment('nombre_votes');
        }

        return to_route('admin.votes.index')->with('success', 'Vote mis à jour.');
    }

    // Supprime un vote et décrémente le compteur si confirmé
    public function destroy(Votes $vote)
    {
        if ($vote->statut === 'confirme') {
            $vote->candidat->decrement('nombre_votes');
        }

        $vote->delete();
        return to_route('admin.votes.index')->with('success', 'Vote supprimé.');
    }
}
