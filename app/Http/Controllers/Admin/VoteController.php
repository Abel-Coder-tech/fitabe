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
    public function index(Request $request)
    {
        $votes = $this->queryFiltree($request)->latest()->paginate($request->integer('per_page', 10));

        $prixDuVote = Parametre::getInt('prix_ovation', 100);
        $afficherCompteur = Parametres::where('cle', 'afficher_compteur')->value('valeur') === '1';
        $dateDebut = Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $dateFin = Parametres::where('cle', 'date_fin_vote')->value('valeur');
        $dateFinale = Parametres::where('cle', 'date_finale')->value('valeur');

        $voteMode = Parametre::voteMode();

        $filtres = $this->filtres($request);
        $operateurs = Votes::query()->select('operateur')->distinct()->whereNotNull('operateur')->orderBy('operateur')->pluck('operateur');
        $pays = Votes::query()->select('pays')->distinct()->whereNotNull('pays')->orderBy('pays')->pluck('pays');

        return view('admin.votes.index', compact(
            'votes', 'voteMode', 'prixDuVote', 'afficherCompteur',
            'dateDebut', 'dateFin', 'dateFinale', 'filtres', 'operateurs', 'pays'
        ));
    }

    // Filtres de la liste (partagés par la page et l'export CSV)
    protected function filtres(Request $request): array
    {
        return [
            'statut' => trim((string) $request->input('statut')),
            'operateur' => trim((string) $request->input('operateur')),
            'pays' => trim((string) $request->input('pays')),
            'date_debut' => trim((string) $request->input('date_debut')),
            'date_fin' => trim((string) $request->input('date_fin')),
        ];
    }

    protected function queryFiltree(Request $request)
    {
        $f = $this->filtres($request);

        return Votes::query()
            ->with('candidat')
            ->when($f['statut'] && $f['statut'] !== 'tous', fn ($q) => $q->where('statut', $f['statut']))
            ->when($f['operateur'] && $f['operateur'] !== 'tous', fn ($q) => $q->where('operateur', $f['operateur']))
            ->when($f['pays'] && $f['pays'] !== 'tous', fn ($q) => $q->where('pays', $f['pays']))
            ->when($f['date_debut'], fn ($q) => $q->whereDate('created_at', '>=', $f['date_debut']))
            ->when($f['date_fin'], fn ($q) => $q->whereDate('created_at', '<=', $f['date_fin']));
    }

    // Export CSV (respecte les filtres courants)
    public function export(Request $request)
    {
        $query = $this->queryFiltree($request);

        $filename = 'ovations-' . now()->format('Y-m-d-Hi') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            // BOM UTF-8 pour que Excel affiche correctement les accents
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ID', 'Référence', 'Candidat', 'Client (téléphone)', 'Email', 'Quantité',
                'Montant (FCFA)', 'Frais (FCFA)', 'Opérateur', 'Moyen de paiement',
                'Statut', 'Pays', 'Date création', 'Confirmé le',
            ]);

            $query->chunk(500, function ($votes) use ($out) {
                foreach ($votes as $v) {
                    fputcsv($out, [
                        $v->id,
                        $v->transaction_id,
                        $v->candidat?->display_name ?? $v->candidat?->nom ?? 'N/A',
                        $v->telephone ?? '',
                        $v->email ?? '',
                        $v->quantite,
                        $v->montant ?? '',
                        $v->frais ?? '',
                        $v->operateur ?? '',
                        $v->moyen_paiement ?? '',
                        match ($v->statut) {
                            'confirme' => 'Confirmé',
                            'rejete' => 'Rejeté',
                            default => 'En attente',
                        },
                        $v->pays ?? '',
                        $v->created_at?->format('d/m/Y H:i') ?? '',
                        $v->webhook_recu_le?->format('d/m/Y H:i') ?? '',
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
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
