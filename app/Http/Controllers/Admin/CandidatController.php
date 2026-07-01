<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use Illuminate\Http\Request;

class CandidatController extends Controller
{
    public function index()
    {
        $candidats = Candidats::orderedByVotes()->paginate(20);
        return view('admin.candidats.index', compact('candidats'));
    }

    public function create()
    {
        return view('admin.candidats.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'nom.required' => 'Le nom du candidat est requis.',
            'nom.max' => 'Le nom ne doit pas dépasser :max caractères.',
            'categorie.required' => 'La catégorie est requise.',
            'categorie.max' => 'La catégorie ne doit pas dépasser :max caractères.',
            'numero_scene.integer' => 'Le numéro de scène doit être un nombre entier.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ];

        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'nom_scene' => 'nullable|string|max:150',
            'categorie' => 'required|string|max:100',
            'numero_scene' => 'nullable|integer',
            'photo' => 'nullable|image|max:2048',
            'biographie' => 'nullable|string',
        ], $messages);

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
        return view('admin.candidats.edit', compact('candidat'));
    }

    public function update(Request $request, Candidats $candidat)
    {
        $messages = [
            'nom.required' => 'Le nom du candidat est requis.',
            'nom.max' => 'Le nom ne doit pas dépasser :max caractères.',
            'categorie.required' => 'La catégorie est requise.',
            'categorie.max' => 'La catégorie ne doit pas dépasser :max caractères.',
            'numero_scene.integer' => 'Le numéro de scène doit être un nombre entier.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ];

        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'nom_scene' => 'nullable|string|max:150',
            'categorie' => 'required|string|max:100',
            'numero_scene' => 'nullable|integer',
            'photo' => 'nullable|image|max:2048',
            'biographie' => 'nullable|string',
        ], $messages);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $candidat->update($validated);

        return to_route('admin.candidats.index')->with('success', 'Candidat mis à jour avec succès.');
    }

    public function destroy(Candidats $candidat)
    {
        $candidat->delete();
        return to_route('admin.candidats.index')->with('success', 'Candidat supprimé avec succès.');
    }
}
