<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use App\Models\PlacesCategorie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CandidatController extends Controller
{
    public function index()
    {
        $categories = collect(Candidats::CATEGORIES)->map(fn ($cat) => (object) [
            'categorie' => $cat,
            'places' => PlacesCategorie::pour($cat),
            'candidats' => Candidats::byCategory($cat)->orderedByScene()->get(),
        ]);

        $total = Candidats::count();

        return view('admin.candidats.index', compact('categories', 'total'));
    }

    public function create()
    {
        $placesParCategorie = collect(Candidats::CATEGORIES)->mapWithKeys(fn ($cat) => [$cat => PlacesCategorie::pour($cat)]);

        return view('admin.candidats.create', compact('placesParCategorie'));
    }

    public function store(Request $request)
    {
        // Normalise « 01 » -> « 1 » pour les champs numériques (même « integer » refuse « 01 »)
        $request->merge([
            'numero_scene' => preg_replace('/^0+(?=\d)/', '', (string) $request->input('numero_scene')),
        ]);

        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'nom_scene' => 'nullable|string|max:150',
            'categorie' => ['required', 'string', Rule::in(Candidats::CATEGORIES)],
            'numero_scene' => 'nullable|integer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'biographie' => 'nullable|string|max:500',
        ],
        [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 150 caractères.',
            'nom_scene.max' => 'Le nom de scène ne doit pas dépasser 150 caractères.',
            'categorie.required' => 'La catégorie est requise.',
            'categorie.in' => 'La catégorie sélectionnée est invalide.',
            'numero_scene.integer' => 'Le numéro de scène doit être un entier.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'Le fichier doit être au format jpeg, png, jpg ou gif.',
            'photo.required' => 'La photo est obligatoire.',
            'photo.max' => 'La taille maximal de l\'image ne doit pas dépasser 2 Mo.',
            'biographie.string' => 'La biographie doit être une chaîne de caractères.',
            'biographie.max' => 'La biographie ne doit pas dépasser 500 caractères.',
        ]);

        $categorie = $validated['categorie'];
        $places = PlacesCategorie::pour($categorie);

        if (Candidats::byCategory($categorie)->count() >= $places) {
            return back()->withErrors([
                'categorie' => "La catégorie « {$categorie} » est complète ({$places} places maximum).",
            ])->withInput();
        }

        if ($request->filled('numero_scene')) {
            $numero = (int) $request->input('numero_scene');
            if ($numero < 1 || $numero > $places) {
                return back()->withErrors([
                    'numero_scene' => "Le numéro de scène doit être compris entre 1 et {$places} pour « {$categorie} ».",
                ])->withInput();
            }
            if (Candidats::where('categorie', $categorie)->where('numero_scene', $numero)->exists()) {
                return back()->withErrors([
                    'numero_scene' => "Le numéro de scène {$numero} est déjà attribué dans « {$categorie} ».",
                ])->withInput();
            }
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Candidats::create($validated);

        return to_route('admin.candidats.index')->with('success', 'Candidat créé avec succès.');
    }

    public function show(Candidats $candidat)
    {
        return view('admin.candidats.show', compact('candidat'));
    }

    public function edit(Candidats $candidat)
    {
        $placesParCategorie = collect(Candidats::CATEGORIES)->mapWithKeys(fn ($cat) => [$cat => PlacesCategorie::pour($cat)]);

        return view('admin.candidats.edit', compact('candidat', 'placesParCategorie'));
    }

    public function update(Request $request, Candidats $candidat)
    {
        // Normalise « 01 » -> « 1 » pour les champs numériques (même « integer » refuse « 01 »)
        $request->merge([
            'numero_scene' => preg_replace('/^0+(?=\d)/', '', (string) $request->input('numero_scene')),
        ]);

        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'nom_scene' => 'nullable|string|max:150',
            'categorie' => ['required', 'string', Rule::in(Candidats::CATEGORIES)],
            'numero_scene' => 'nullable|integer',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'biographie' => 'nullable|string|max:500',
        ],
        [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 150 caractères.',
            'nom_scene.max' => 'Le nom de scène ne doit pas dépasser 150 caractères.',
            'categorie.required' => 'La catégorie est requise.',
            'categorie.in' => 'La catégorie sélectionnée est invalide.',
            'numero_scene.integer' => 'Le numéro de scène doit être un entier.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'Le fichier doit être au format jpeg, png, jpg ou gif.',
            'photo.max' => 'La taille maximal de l\'image ne doit pas dépasser 2 Mo.',
            'biographie.string' => 'La biographie doit être une chaîne de caractères.',
            'biographie.max' => 'La biographie ne doit pas dépasser 500 caractères.',
        ]);

        $categorie = $validated['categorie'];
        $places = PlacesCategorie::pour($categorie);

        if (Candidats::byCategory($categorie)->where('id', '!=', $candidat->id)->count() >= $places) {
            return back()->withErrors([
                'categorie' => "La catégorie « {$categorie} » est complète ({$places} places maximum).",
            ])->withInput();
        }

        if ($request->filled('numero_scene')) {
            $numero = (int) $request->input('numero_scene');
            if ($numero < 1 || $numero > $places) {
                return back()->withErrors([
                    'numero_scene' => "Le numéro de scène doit être compris entre 1 et {$places} pour « {$categorie} ».",
                ])->withInput();
            }
            if (Candidats::where('categorie', $categorie)->where('numero_scene', $numero)->where('id', '!=', $candidat->id)->exists()) {
                return back()->withErrors([
                    'numero_scene' => "Le numéro de scène {$numero} est déjà attribué dans « {$categorie} ».",
                ])->withInput();
            }
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $candidat->update($validated);

        return to_route('admin.candidats.index')->with('success', 'Candidat mis à jour avec succès.');
    }

    public function destroy(Candidats $candidat)
    {
        $candidat->forceDelete();
        return to_route('admin.candidats.index')->with('success', 'Candidat supprimé avec succès.');
    }

    public function updatePlaces(Request $request)
    {
        // Normalise « 01 » -> « 1 » pour que la règle « integer » l'accepte
        $request->merge([
            'places' => preg_replace('/^0+(?=\d)/', '', (string) $request->input('places')),
        ]);

        $validated = $request->validate([
            'categorie' => ['required', 'string', Rule::in(Candidats::CATEGORIES)],
            'places' => 'required|integer|min:1|max:100',
        ], [
            'categorie.required' => 'La catégorie est requise.',
            'categorie.in' => 'La catégorie sélectionnée est invalide.',
            'places.required' => 'Le nombre de places est requis.',
            'places.integer' => 'Le nombre de places doit être un entier.',
            'places.min' => 'Le nombre de places doit être au moins 1.',
            'places.max' => 'Le nombre de places ne doit pas dépasser 100.',
        ]);

        PlacesCategorie::updateOrCreate(
            ['categorie' => $validated['categorie']],
            ['places' => $validated['places']]
        );

        return back()->with('success', "Places mises à jour pour « {$validated['categorie']} » : {$validated['places']}.");
    }

    public function updateNoteJury(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:candidates,id',
            'note' => 'nullable|numeric|min:0|max:20',
        ]);

        Candidats::where('id', $validated['id'])->update([
            'note_jury' => $validated['note'] !== '' ? $validated['note'] : null,
        ]);

        return response()->json(['success' => true]);
    }
}
