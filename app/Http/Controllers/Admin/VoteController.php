<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Votes;
use App\Models\Candidats;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function index()
    {
        $votes = Votes::with('candidat')->latest()->paginate(20);
        return view('admin.votes.index', compact('votes'));
    }

    public function show(Votes $vote)
    {
        $vote->load('candidat');
        return view('admin.votes.show', compact('vote'));
    }

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

    public function destroy(Votes $vote)
    {
        if ($vote->statut === 'confirme') {
            $vote->candidat->decrement('nombre_votes');
        }

        $vote->delete();
        return to_route('admin.votes.index')->with('success', 'Vote supprimé.');
    }
}
