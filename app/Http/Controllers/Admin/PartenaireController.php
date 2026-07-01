<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partenaires;
use Illuminate\Http\Request;

class PartenaireController extends Controller
{
    public function index()
    {
        $partenaires = Partenaires::ordered()->paginate(20);
        return view('admin.partenaires.index', compact('partenaires'));
    }

    public function create()
    {
        return view('admin.partenaires.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'nom.required' => 'Le nom du partenaire est requis.',
            'nom.max' => 'Le nom ne doit pas dépasser :max caractères.',
            'logo.image' => 'Le fichier doit être une image.',
            'logo.max' => 'Le logo ne doit pas dépasser 2 Mo.',
            'site_web.url' => 'Le site web doit être une URL valide.',
            'ordre.integer' => 'L\'ordre doit être un nombre entier.',
        ];

        $validated = $request->validate([
            'nom' => 'required|string|max:200',
            'logo' => 'nullable|image|max:2048',
            'site_web' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
        ], $messages);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Partenaires::create($validated);

        return to_route('admin.partenaires.index')->with('success', 'Partenaire créé avec succès.');
    }

    public function show(Partenaires $partenaire)
    {
        return view('admin.partenaires.show', compact('partenaire'));
    }

    public function edit(Partenaires $partenaire)
    {
        return view('admin.partenaires.edit', compact('partenaire'));
    }

    public function update(Request $request, Partenaires $partenaire)
    {
        $messages = [
            'nom.required' => 'Le nom du partenaire est requis.',
            'nom.max' => 'Le nom ne doit pas dépasser :max caractères.',
            'logo.image' => 'Le fichier doit être une image.',
            'logo.max' => 'Le logo ne doit pas dépasser 2 Mo.',
            'site_web.url' => 'Le site web doit être une URL valide.',
            'ordre.integer' => 'L\'ordre doit être un nombre entier.',
        ];

        $validated = $request->validate([
            'nom' => 'required|string|max:200',
            'logo' => 'nullable|image|max:2048',
            'site_web' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
        ], $messages);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $partenaire->update($validated);

        return to_route('admin.partenaires.index')->with('success', 'Partenaire mis à jour avec succès.');
    }

    public function destroy(Partenaires $partenaire)
    {
        $partenaire->delete();
        return to_route('admin.partenaires.index')->with('success', 'Partenaire supprimé avec succès.');
    }
}
