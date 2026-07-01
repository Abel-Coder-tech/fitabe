<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medias;
use App\Models\Candidats;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        $medias = Medias::with('candidat')->latest()->paginate(20);
        return view('admin.media.index', compact('medias'));
    }

    public function create()
    {
        $candidats = Candidats::all();
        return view('admin.media.create', compact('candidats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'nullable|string|max:200',
            'type' => 'required|string|max:50',
            'fichier' => 'required|file|max:10240',
            'candidat_id' => 'nullable|exists:candidats,id',
        ], [
            'type.required' => 'Le type de média est requis.',
            'fichier.required' => 'Le fichier est requis.',
            'fichier.file' => 'Le fichier doit être un fichier valide.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
            'candidat_id.exists' => 'Le candidat sélectionné n\'existe pas.',
        ]);

        $validated['url'] = $request->file('fichier')->store('medias', 'public');
        unset($validated['fichier']);

        Medias::create($validated);

        return to_route('admin.medias.index')->with('success', 'Média ajouté avec succès.');
    }

    public function show(Medias $media)
    {
        $media->load('candidat');
        return view('admin.media.show', compact('media'));
    }

    public function edit(Medias $media)
    {
        $candidats = Candidats::all();
        return view('admin.media.edit', compact('media', 'candidats'));
    }

    public function update(Request $request, Medias $media)
    {
        $validated = $request->validate([
            'titre' => 'nullable|string|max:200',
            'type' => 'required|string|max:50',
            'fichier' => 'nullable|file|max:10240',
            'candidat_id' => 'nullable|exists:candidats,id',
        ], [
            'type.required' => 'Le type de média est requis.',
            'fichier.file' => 'Le fichier doit être un fichier valide.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
            'candidat_id.exists' => 'Le candidat sélectionné n\'existe pas.',
        ]);

        if ($request->hasFile('fichier')) {
            $validated['url'] = $request->file('fichier')->store('medias', 'public');
        }
        unset($validated['fichier']);

        $media->update($validated);

        return to_route('admin.medias.index')->with('success', 'Média mis à jour avec succès.');
    }

    public function destroy(Medias $media)
    {
        $media->delete();
        return to_route('admin.medias.index')->with('success', 'Média supprimé avec succès.');
    }
}
