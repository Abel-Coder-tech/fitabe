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
        $resultats = Resultat::where('annee_edition', '=', $annee, 'and')->orderBy('categorie')->orderBy('prix')->get()
            ->groupBy('categorie');
        return view('admin.resultats.show', compact('resultats', 'annee'));
    }

    // Affiche le formulaire d'édition d'un résultat
    public function edit(Resultat $resultat)
    {
        return view('admin.resultats.edit', compact('resultat'));
    }

    // Met à jour la note jury et recalcule le score final
    public function update(Request $request, Resultat $resultat)
    {
        $validated = $request->validate([
            'note_jury' => 'nullable|numeric|min:0|max:20',
        ], 
        [
            'note_jury.numeric' => 'La note du jury doit être un nombre.',
            'note_jury.min' => 'La note du jury ne peut pas être inférieure à :min.',
            'note_jury.max' => 'La note du jury ne peut pas dépasser :max.',
        ]);
    

        $resultat->note_jury = $validated['note_jury'];
        $resultat->recalculerScoreFinal();
        $resultat->save();

        return to_route('admin.resultats.show', $resultat->annee_edition)
            ->with('success', 'Note jury mise à jour.');
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
}
