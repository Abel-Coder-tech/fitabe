<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medias;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    // Affiche la liste paginée des médias (photos et vidéos séparées)
    public function index()
    {
        $perPage = request()->integer('per_page', 10);

        $photos = Medias::where('type', 'photo')->latest()->paginate($perPage)->withQueryString();
        $videos = Medias::where('type', 'video')->latest()->paginate($perPage)->withQueryString();

        return view('admin.media.index', compact('photos', 'videos'));
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
            'fichier' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:1024',
            'lien_youtube' => 'nullable|url|max:255',
            'annee_edition' => 'nullable|string|max:4',
        ], [
            'type.required' => 'Le type de média est requis.',
            'type.in' => 'Le type doit être photo ou video.',
            'fichier.file' => 'Le fichier doit être un fichier valide.',
            'fichier.mimes' => 'Le fichier doit être une image (jpeg, png, gif, webp) ou une vidéo (mp4, mov, avi).',
            'fichier.max' => 'Le fichier ne doit pas dépasser 1 Mo.',
            'lien_youtube.url' => 'Le lien YouTube doit être une URL valide.',
        ]);

        if ($request->type === 'photo') {
            $request->validate(['fichier' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:1024'], ['fichier.required' => 'Le fichier photo est requis.']);
            $validated['url'] = $request->file('fichier')->store('medias', 'public');
            $validated['lien_youtube'] = null;
        } else {
            $request->validate(['lien_youtube' => 'required|url|max:255'], [
                'lien_youtube.required' => 'Le lien YouTube est requis.',
                'lien_youtube.url' => 'Le lien YouTube doit être une URL valide.',
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
            'fichier' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:1024',
            'lien_youtube' => 'nullable|url|max:255',
            'annee_edition' => 'nullable|string|max:10',
        ], [
            'type.required' => 'Le type de média est requis.',
            'type.in' => 'Le type doit être photo ou video.',
            'fichier.file' => 'Le fichier doit être un fichier valide.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 1 Mo.',
            'lien_youtube.url' => 'Le lien YouTube doit être une URL valide.',
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
