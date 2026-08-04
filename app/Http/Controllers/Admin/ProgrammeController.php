<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programmes;
use App\Models\ProgrammeDate;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function index()
    {
        $programmes = Programmes::ordered()->withCount('dates')->paginate(request()->integer('per_page', 10));
        return view('admin.programmes.index', compact('programmes'));
    }

    public function create()
    {
        return view('admin.programmes.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'titre.required' => 'Le titre est requis.',
            'titre.max' => 'Le titre ne doit pas dépasser :max caractères.',
            'date_programme.required' => 'La date du programme est requise.',
            'date_programme.date' => 'La date doit être une date valide.',
            'lieu.max' => 'Le lieu ne doit pas dépasser :max caractères.',
            'ordre.integer' => 'L\'ordre d\'affichage doit être un nombre entier.',
            'ordre.min' => 'L\'ordre d\'affichage doit être un nombre positif ou nul.',
            'icone.max' => 'L\'icône ne doit pas dépasser :max caractères.',
            'couleur_bordure.max' => 'La couleur de bordure ne doit pas dépasser :max caractères.',
            'categorie.max' => 'La catégorie ne doit pas dépasser :max caractères.',
            'dates_date.*.date' => 'Chaque sous-date doit être une date valide.',
            'dates_titre.*.max' => 'Le titre d\'une sous-date ne doit pas dépasser :max caractères.',
            'dates_lieu.*.max' => 'Le lieu d\'une sous-date ne doit pas dépasser :max caractères.',
            'dates_ordre.*.integer' => 'L\'ordre d\'une sous-date doit être un nombre entier.',
        ];

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:50',
            'couleur_bordure' => 'nullable|string|max:7',
            'date_programme' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'categorie' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer|min:0',
            'est_actif' => 'nullable|boolean',
            'dates_date' => 'nullable|array',
            'dates_date.*' => 'nullable|date',
            'dates_titre.*' => 'nullable|string|max:200',
            'dates_lieu.*' => 'nullable|string|max:255',
            'dates_ordre.*' => 'nullable|integer|min:0',
        ], $messages);

        $validated['ordre_affichage'] = $validated['ordre'] ?? 0;
        unset($validated['ordre']);

        $programme = Programmes::create($validated);

        $this->syncDates($programme, $request);

        return to_route('admin.programmes.index')->with('success', 'Programme créé avec succès.');
    }

    public function show(Programmes $programme)
    {
        $programme->load('dates');
        return view('admin.programmes.show', compact('programme'));
    }

    public function edit(Programmes $programme)
    {
        $programme->load('dates');
        return view('admin.programmes.edit', compact('programme'));
    }

    public function update(Request $request, Programmes $programme)
    {
        $messages = [
            'titre.required' => 'Le titre est requis.',
            'titre.max' => 'Le titre ne doit pas dépasser :max caractères.',
            'date_programme.required' => 'La date du programme est requise.',
            'date_programme.date' => 'La date doit être une date valide.',
            'lieu.max' => 'Le lieu ne doit pas dépasser :max caractères.',
            'ordre.integer' => 'L\'ordre d\'affichage doit être un nombre entier.',
            'ordre.min' => 'L\'ordre d\'affichage doit être un nombre positif ou nul.',
            'icone.max' => 'L\'icône ne doit pas dépasser :max caractères.',
            'couleur_bordure.max' => 'La couleur de bordure ne doit pas dépasser :max caractères.',
            'categorie.max' => 'La catégorie ne doit pas dépasser :max caractères.',
            'dates_date.*.date' => 'Chaque sous-date doit être une date valide.',
            'dates_titre.*.max' => 'Le titre d\'une sous-date ne doit pas dépasser :max caractères.',
            'dates_lieu.*.max' => 'Le lieu d\'une sous-date ne doit pas dépasser :max caractères.',
            'dates_ordre.*.integer' => 'L\'ordre d\'une sous-date doit être un nombre entier.',
        ];

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:50',
            'couleur_bordure' => 'nullable|string|max:7',
            'date_programme' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'categorie' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer|min:0',
            'est_actif' => 'nullable|boolean',
            'dates_date' => 'nullable|array',
            'dates_date.*' => 'nullable|date',
            'dates_titre.*' => 'nullable|string|max:200',
            'dates_lieu.*' => 'nullable|string|max:255',
            'dates_ordre.*' => 'nullable|integer|min:0',
        ], $messages);

        $validated['ordre_affichage'] = $validated['ordre'] ?? 0;
        unset($validated['ordre']);

        $programme->update($validated);
        $this->syncDates($programme, $request);

        return to_route('admin.programmes.index')->with('success', 'Programme mis à jour avec succès.');
    }

    public function destroy(Programmes $programme)
    {
        $programme->delete();
        return to_route('admin.programmes.index')->with('success', 'Programme supprimé avec succès.');
    }

    private function syncDates(Programmes $programme, Request $request): void
    {
        if (!$request->has('dates_date')) {
            return;
        }

        $existingIds = $programme->dates()->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($request->input('dates_date') as $i => $date) {
            if (empty($date)) {
                continue;
            }
            $data = [
                'titre' => $request->input("dates_titre.$i"),
                'date' => $date,
                'lieu' => $request->input("dates_lieu.$i"),
                'ordre' => $request->input("dates_ordre.$i", 0),
            ];

            if ($id = $request->input("dates_id.$i")) {
                $submittedIds[] = (int) $id;
                ProgrammeDate::where('id', $id)->where('programme_id', $programme->id)->update($data);
            } else {
                $sd = $programme->dates()->create($data);
                $submittedIds[] = $sd->id;
            }
        }

        $toDelete = array_diff($existingIds, $submittedIds);
        if (!empty($toDelete)) {
            ProgrammeDate::whereIn('id', $toDelete)->delete();
        }
    }
}
