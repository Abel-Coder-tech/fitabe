<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametres;
use App\Support\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function index()
    {
        $parametres = Parametres::query()->orderBy('id')->get();

        $grouped = $parametres->groupBy(function ($p) {
            $prefix = explode('_', $p->cle)[0] ?? 'autre';
            return match ($prefix) {
                'edition', 'logo' => 'Général',
                'contact', 'social', 'hero' => 'Communication',
                'prix', 'devise', 'seuil', 'texte_info' => 'Vote / Ovation',
                'texte_media', 'medias' => 'Médiathèque',
                default => 'Autre',
            };
        });

        return view('admin.parametres.index', compact('grouped'));
    }

    public function create()
    {
        return view('admin.parametres.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cle' => 'required|string|max:100|unique:parametres,cle',
            'valeur' => 'nullable|string',
        ], [
            'cle.required' => 'La clé est requise.',
            'cle.max' => 'La clé ne doit pas dépasser :max caractères.',
            'cle.unique' => 'Cette clé est déjà utilisée.',
        ]);

        Parametres::create($validated);
        Parametre::flush();

        return to_route('admin.parametres.index')->with('success', 'Paramètre créé avec succès.');
    }

    public function show(Parametres $parametre)
    {
        return to_route('admin.parametres.edit', $parametre);
    }

    public function edit(Parametres $parametre)
    {
        return view('admin.parametres.edit', compact('parametre'));
    }

    public function update(Request $request, Parametres $parametre)
    {
        $validated = $request->validate([
            'valeur' => 'nullable|string',
        ]);

        if ($request->hasFile('valeur_file') && $request->file('valeur_file')->isValid()) {
            $validated['valeur'] = $request->file('valeur_file')->store('logos', 'public');
        }

        $parametre->update($validated);
        Parametre::flush();

        return to_route('admin.parametres.index')->with('success', 'Paramètre mis à jour avec succès.');
    }

    public function destroy(Parametres $parametre)
    {
        $parametre->forceDelete();
        Parametre::flush();

        return to_route('admin.parametres.index')->with('success', 'Paramètre supprimé avec succès.');
    }
}
