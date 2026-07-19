<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametres;
use App\Models\Votes;
use App\Models\Candidats;
use Carbon\Carbon;

class VoteController extends Controller
{
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

    // Affiche la liste des votes avec les paramètres
    public function index()
    {
        $votes = Votes::with('candidat')->latest()->paginate(20);

        $prixDuVote = 100;
        $afficherCompteur = Parametres::where('cle', 'afficher_compteur')->value('valeur') === '1';
        $dateDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $dateFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');

        $voteMode = $this->computeVoteMode($dateDebut, $dateFin);

        return view('admin.votes.index', compact(
            'votes', 'voteMode', 'prixDuVote', 'afficherCompteur',
            'dateDebut', 'dateFin'
        ));
    }

    // Affiche le détail d'un vote
    public function show(Votes $vote)
    {
        $vote->load('candidat');
        $prixDuVote = 100;
        return view('admin.votes.show', compact('vote', 'prixDuVote'));
    }

    // Supprime un vote (super_admin uniquement)
    public function destroy(Votes $vote)
    {
        if (request()->user()->role !== 'super_admin') {
            abort(403, 'Seul un super administrateur peut supprimer une ovation.');
        }

        if ($vote->statut === 'confirme' && $vote->candidat) {
            $vote->candidat->decrement('nombre_votes');
        }

        $vote->delete();
        return to_route('admin.votes.index')->with('success', 'Vote supprimé.');
    }

    // Supprime TOUS les votes et réinitialise les compteurs
    public function clearAll()
    {
        Votes::truncate();
        Candidats::query()->update(['nombre_votes' => 0]);

        return to_route('admin.votes.index')->with('success', 'Toutes les ovations ont été supprimées.');
    }

    public function toggle()
    {
        $statut = Parametres::where('cle', 'statut_vote')->value('valeur');

        if ($statut === 'active') {
            Parametres::updateOrCreate(['cle' => 'statut_vote'], ['valeur' => 'cloture']);
            try {
                $annee = date('Y');
                $service = app(\App\Services\ResultatService::class);
                $service->generer($annee);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Erreur génération résultats: ' . $e->getMessage());
            }
            return to_route('admin.votes.index')->with('success', 'Vote clôturé. Résultats générés.');
        }

        Parametres::updateOrCreate(['cle' => 'statut_vote'], ['valeur' => 'active']);
        return to_route('admin.votes.index')->with('success', 'Vote démarré.');
    }
}
