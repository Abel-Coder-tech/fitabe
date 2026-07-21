<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medias;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    // Affiche la liste paginée des médias
    public function index()
    {
        $medias = Medias::orderBy('id', 'asc')->paginate(20);
        return view('admin.media.index', compact('medias'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        return view('admin.media.create');
    }

    // Enregistre un nouveau média (photo ou vidéo)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:photo,video',
            'titre' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:10240',
            'lien_youtube' => 'nullable|string|max:255',
            'annee_edition' => 'nullable|string|max:10',
        ], [
            'type.required' => 'Le type de média est requis.',
            'type.in' => 'Le type doit être photo ou video.',
            'fichier.file' => 'Le fichier doit être un fichier valide.',
            'fichier.mimes' => 'Le fichier doit être une image (jpeg, png, gif, webp) ou une vidéo (mp4, mov, avi).',
            'fichier.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        if ($request->type === 'photo') {
            $request->validate(['fichier' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:10240'], ['fichier.required' => 'Le fichier photo est requis.']);
            $validated['url'] = $request->file('fichier')->store('medias', 'public');
            $validated['lien_youtube'] = null;
        } else {
            $request->validate(['lien_youtube' => 'required|string|max:255'], [
                'lien_youtube.required' => 'Le lien YouTube est requis.',
            ]);
            $validated['url'] = null;
        }

        unset($validated['fichier']);

        Medias::create($validated);

        return to_route('admin.medias.index')->with('success', 'Média ajouté avec succès.');
    }

    // Affiche le détail d'un média
    public function show(Medias $media)
    {
        return view('admin.media.show', compact('media'));
    }

    // Affiche le formulaire d'édition
    public function edit(Medias $media)
    {
        return view('admin.media.edit', compact('media'));
    }

    // Met à jour un média existant
    public function update(Request $request, Medias $media)
    {
        $validated = $request->validate([
            'type' => 'required|in:photo,video',
            'titre' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:10240',
            'lien_youtube' => 'nullable|string|max:255',
            'annee_edition' => 'nullable|string|max:10',
        ], [
            'type.required' => 'Le type de média est requis.',
            'type.in' => 'Le type doit être photo ou video.',
            'fichier.file' => 'Le fichier doit être un fichier valide.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        if ($request->type === 'photo') {
            if ($request->hasFile('fichier')) {
                $validated['url'] = $request->file('fichier')->store('medias', 'public');
            }
            $validated['lien_youtube'] = null;
        } else {
            $validated['url'] = null;
            $validated['lien_youtube'] = $request->lien_youtube;
        }
        unset($validated['fichier']);

        $media->update($validated);

        return to_route('admin.medias.index')->with('success', 'Média mis à jour avec succès.');
    }

    // Supprime un média
    public function destroy(Medias $media)
    {
        $media->forceDelete();
        return to_route('admin.medias.index')->with('success', 'Média supprimé avec succès.');
    }
}
