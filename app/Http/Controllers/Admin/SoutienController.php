<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Soutien;
use Illuminate\Http\Request;

class SoutienController extends Controller
{
    public function index()
    {
        $soutiens = Soutien::ordered()->paginate(20);
        return view('admin.soutiens.index', compact('soutiens'));
    }

    public function create()
    {
        return view('admin.soutiens.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:200',
            'photo' => 'required|image|max:2048',
            'citation' => 'nullable|string',
            'titre' => 'nullable|string|max:200',
            'role_parrain' => 'nullable|string|max:100',
        ], [
            'nom.required' => 'Le nom est requis.',
            'photo.required' => 'La photo est requise.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'La photo ne doit pas dépasser 2 Mo.',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('soutiens', 'public');
        }

        Soutien::create($validated);

        return to_route('admin.soutiens.index')->with('success', 'Soutien créé avec succès.');
    }

    public function show(Soutien $soutien)
    {
        return to_route('admin.soutiens.edit', $soutien);
    }

    public function edit(Soutien $soutien)
    {
        return view('admin.soutiens.edit', compact('soutien'));
    }

    public function update(Request $request, Soutien $soutien)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:200',
            'photo' => 'nullable|image|max:2048',
            'citation' => 'nullable|string',
            'titre' => 'nullable|string|max:200',
            'role_parrain' => 'nullable|string|max:100',
        ], [
            'nom.required' => 'Le nom est requis.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'La photo ne doit pas dépasser 2 Mo.',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('soutiens', 'public');
        }

        $soutien->update($validated);

        return to_route('admin.soutiens.index')->with('success', 'Soutien mis à jour avec succès.');
    }

    public function destroy(Soutien $soutien)
    {
        $soutien->forceDelete();
        return to_route('admin.soutiens.index')->with('success', 'Soutien supprimé avec succès.');
    }
}
