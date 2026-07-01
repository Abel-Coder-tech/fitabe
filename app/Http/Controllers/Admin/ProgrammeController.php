<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programmes;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function index()
    {
        $programmes = Programmes::ordered()->paginate(20);
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
            'ordre.integer' => 'L\'ordre doit être un nombre entier.',
        ];

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'date_programme' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'categorie' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer|min:0',
            'est_actif' => 'nullable|boolean',
        ], $messages);

        Programmes::create($validated);

        return to_route('admin.programmes.index')->with('success', 'Programme créé avec succès.');
    }

    public function show(Programmes $programme)
    {
        return view('admin.programmes.show', compact('programme'));
    }

    public function edit(Programmes $programme)
    {
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
            'ordre.integer' => 'L\'ordre doit être un nombre entier.',
        ];

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'date_programme' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'categorie' => 'nullable|string|max:100',
            'ordre' => 'nullable|integer|min:0',
            'est_actif' => 'nullable|boolean',
        ], $messages);

        $programme->update($validated);

        return to_route('admin.programmes.index')->with('success', 'Programme mis à jour avec succès.');
    }

    public function destroy(Programmes $programme)
    {
        $programme->delete();
        return to_route('admin.programmes.index')->with('success', 'Programme supprimé avec succès.');
    }
}
