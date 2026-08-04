<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametres;
use App\Models\Votes;
use App\Models\VoteLog;
use App\Models\Candidats;
use App\Support\Parametre;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VoteController extends Controller
{
    // Helper : détermine le mode vote (statut manuel prioritaire, sinon dates) — voir Parametre::voteMode()

    // Affiche la liste des votes avec les paramètres
    public function index()
    {
        $votes = Votes::with('candidat')->latest()->paginate(request()->integer('per_page', 10));

        $prixDuVote = Parametre::getInt('prix_ovation', 100);
        $afficherCompteur = Parametres::where('cle', 'afficher_compteur')->value('valeur') === '1';
        $dateDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $dateFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');
        $dateFinale = Parametres::where('cle', 'date_finale')->value('valeur');

        $voteMode = Parametre::voteMode();

        return view('admin.votes.index', compact(
            'votes', 'voteMode', 'prixDuVote', 'afficherCompteur',
            'dateDebut', 'dateFin', 'dateFinale'
        ));
    }

    // Affiche le détail d'un vote
    public function show(Votes $vote)
    {
        $vote->load('candidat');
        $prixDuVote = Parametre::getInt('prix_ovation', 100);
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

    // Boutons rapides « Mode du site » : action = demarrer | cloturer
    public function toggle(Request $request)
    {
        $action = $request->input('action', 'demarrer');

        // Clôture rapide : stoppe immédiatement les votes (prioritaire jusqu'à relance)
        if ($action === 'cloturer') {
            Parametres::updateOrCreate(['cle' => 'statut_vote'], ['valeur' => 'cloture']);
            Parametre::flush();

            try {
                $annee = date('Y');
                $service = app(\App\Services\ResultatService::class);
                $service->generer($annee);
            } catch (\Throwable $e) {
                Log::error('Erreur génération résultats: ' . $e->getMessage());
            }

            return to_route('admin.votes.index')->with('success', 'Vote clôturé. Les résultats ont été générés.');
        }

        // Démarrage rapide : l'heure de clic devient l'heure de démarrage réelle,
        // la fin effective est calculée selon la durée planifiée
        $dateDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $dateFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');

        if (!$dateDebut || !$dateFin) {
            return to_route('admin.votes.index')
                ->with('error', 'Veuillez d\'abord définir une date de début et de fin dans le menu Ovations avant de démarrer le vote.');
        }

        Parametres::updateOrCreate(['cle' => 'debut_effectif'], ['valeur' => Carbon::now()->toDateTimeString()]);
        Parametres::updateOrCreate(['cle' => 'statut_vote'], ['valeur' => 'active']);
        Parametre::flush();

        $finEffective = Parametre::finEffective();

        return to_route('admin.votes.index')
            ->with('success', 'Vote démarré. Début effectif : maintenant — fin prévue à ' . ($finEffective ? Carbon::parse($finEffective)->format('d/m/Y H:i') : '?'));
    }

    // Historique des logs de paiement (webhooks Fedapay + callbacks) et leurs causes
    public function logs()
    {
        $logs = VoteLog::query()->latest('created_at')->paginate(request()->integer('per_page', 20));

        return view('admin.votes.logs', compact('logs'));
    }
}
