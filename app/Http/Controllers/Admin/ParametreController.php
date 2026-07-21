<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametres;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function index()
    {
        $parametres = Parametres::query()->paginate(20);
        return view('admin.parametres.index', compact('parametres'));
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
            'cle' => 'required|string|max:100|unique:parametres,cle,' . $parametre->id,
            'valeur' => 'nullable|string',
        ], [
            'cle.required' => 'La clé est requise.',
            'cle.max' => 'La clé ne doit pas dépasser :max caractères.',
            'cle.unique' => 'Cette clé est déjà utilisée.',
        ]);

        $parametre->update($validated);

        return to_route('admin.parametres.index')->with('success', 'Paramètre mis à jour avec succès.');
    }

    public function destroy(Parametres $parametre)
    {
        $parametre->forceDelete();
        return to_route('admin.parametres.index')->with('success', 'Paramètre supprimé avec succès.');
    }
}
