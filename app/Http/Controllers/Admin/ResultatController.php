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

    // Liste les éditions disponibles
    public function index()
    {
        $editions = Resultat::select('annee_edition')->distinct()->orderBy('annee_edition', 'desc')->pluck('annee_edition');
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
        ]);

        $resultat->note_technique = $validated['note_technique'];
        $resultat->note_originalite = $validated['note_originalite'];
        $resultat->note_presence = $validated['note_presence'];
        $resultat->recalculerScoreFinal();
        $resultat->save();

        return to_route('admin.resultats.show', $resultat->annee_edition)
            ->with('success', 'Notes jury mises à jour.');
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
