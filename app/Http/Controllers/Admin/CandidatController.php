<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidats;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'nom_scene' => 'nullable|string|max:150',
            'categorie' => 'required|string,',
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
       $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'nom_scene' => 'nullable|string|max:150',
            'categorie' => ['required', 'string', Rule::in(['Théâtre', 'Percussions', 'Musique', 'Danse Traditionnelle', 'Stylisme/Modélisme', 'Arts Visuels'])],
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
}
