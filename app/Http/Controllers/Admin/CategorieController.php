<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::ordered()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['nom']);
        $validated['ordre'] ??= 0;

        Categorie::create($validated);

        return to_route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(Categorie $categorie)
    {
        return view('admin.categories.edit', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['nom']);

        $categorie->update($validated);

        return to_route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return to_route('admin.categories.index')->with('success', 'Catégorie supprimée.');
    }
}
