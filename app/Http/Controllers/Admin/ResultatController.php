<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resultat;
use App\Services\ResultatService;
use Illuminate\Http\Request;

class ResultatController extends Controller
{
    // Injection du service de résultats
    public function __construct(
        protected ResultatService $resultatService
    ) {}

    // Liste les éditions disponibles avec leur statut de publication
    public function index()
    {
        $editions = Resultat::select('annee_edition')
            ->distinct()
            ->orderBy('annee_edition', 'desc')
            ->get()
            ->map(function ($r) {
                $annee = $r->annee_edition;
                $r->total = Resultat::byEdition($annee)->count();
                $r->publies = Resultat::byEdition($annee)->where('publie', true)->count();
                return $r;
            });
        return view('admin.resultats.index', compact('editions'));
    }

    // Affiche les résultats d'une édition
    public function show(string $annee)
    {
        $resultats = Resultat::where('annee_edition', $annee)->orderBy('categorie')->orderBy('prix')->get()
            ->groupBy('categorie');
        return view('admin.resultats.show', compact('resultats', 'annee'));
    }

    // Affiche le formulaire d'édition d'un résultat
    public function edit(Resultat $resultat)
    {
        return view('admin.resultats.edit', compact('resultat'));
    }

    // Met à jour les notes jury et recalcule le score final
    public function update(Request $request, Resultat $resultat)
    {
        $validated = $request->validate([
            'note_technique' => 'nullable|numeric|min:0|max:20',
            'note_originalite' => 'nullable|numeric|min:0|max:20',
            'note_presence' => 'nullable|numeric|min:0|max:20',
            'note_perfection' => 'nullable|numeric|min:0|max:25',
        ], [
            'note_technique.numeric' => 'La note technique doit être un nombre.',
            'note_technique.min' => 'La note technique doit être comprise entre 0 et 20.',
            'note_technique.max' => 'La note technique doit être comprise entre 0 et 20.',
            'note_originalite.numeric' => 'La note d\'originalité doit être un nombre.',
            'note_originalite.min' => 'La note d\'originalité doit être comprise entre 0 et 20.',
            'note_originalite.max' => 'La note d\'originalité doit être comprise entre 0 et 20.',
            'note_presence.numeric' => 'La note de présence doit être un nombre.',
            'note_presence.min' => 'La note de présence doit être comprise entre 0 et 20.',
            'note_presence.max' => 'La note de présence doit être comprise entre 0 et 20.',
            'note_perfection.numeric' => 'La note de perfection doit être un nombre.',
            'note_perfection.min' => 'La note de perfection doit être comprise entre 0 et 25.',
            'note_perfection.max' => 'La note de perfection doit être comprise entre 0 et 25.',
        ]);

        $resultat->note_technique = $validated['note_technique'];
        $resultat->note_originalite = $validated['note_originalite'];
        $resultat->note_presence = $validated['note_presence'];
        $resultat->note_perfection = $validated['note_perfection'];
        $resultat->recalculerScoreFinal();
        $resultat->save();

        // Réattribue les prix en fonction du score final le plus élevé de la catégorie
        $this->resultatService->reclasser($resultat->annee_edition);

        return to_route('admin.resultats.show', $resultat->annee_edition)
            ->with('success', 'Notes jury mises à jour. Le classement a été recalculé selon le score final le plus élevé.');
    }

    // Régénère tous les résultats pour une édition
    public function regenerer(string $annee)
    {
        // Supprime les résultats existant pour cette édition
        Resultat::byEdition($annee)->delete();
        $this->resultatService->generer($annee);

        return to_route('admin.resultats.show', $annee)
            ->with('success', 'Résultats régénérés pour l\'édition ' . $annee);
    }

    public function togglePublishEdition(string $annee)
    {
        $allPublished = Resultat::where('annee_edition', $annee)->where('publie', false)->exists();

        if ($allPublished) {
            Resultat::where('annee_edition', $annee)->update(['publie' => true]);
            $msg = 'Résultats ' . $annee . ' publiés.';
        } else {
            Resultat::where('annee_edition', $annee)->update(['publie' => false]);
            $msg = 'Résultats ' . $annee . ' dépubliés.';
        }

        return back()->with('success', $msg);
    }

    public function destroy(string $annee)
    {
        $count = Resultat::byEdition($annee)->count();
        if ($count === 0) {
            return back()->with('error', 'Aucun résultat trouvé pour l\'édition ' . $annee);
        }
        Resultat::byEdition($annee)->delete();
        return to_route('admin.resultats.index')
            ->with('success', "{$count} résultat(s) supprimé(s) pour l'édition {$annee}");
    }

    public function publicIndex()
    {
        $annees = Resultat::where('publie', true)->distinct()->orderBy('annee_edition', 'desc')->pluck('annee_edition');

        if ($annees->isEmpty()) {
            return view('public.resultats.index', ['resultats' => collect(), 'annee' => null]);
        }

        $annee = $annees->first();
        $resultats = Resultat::where('annee_edition', $annee)->where('publie', true)
            ->orderBy('categorie')->orderBy('prix')->get()->groupBy('categorie');

        return view('public.resultats.index', compact('resultats', 'annee'));
    }
}
