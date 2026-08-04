<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametres;
use App\Support\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    private array $allowed = [
        'contact_telephone', 'contact_email',
        'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok',
        'hero_titre', 'hero_sous_titre',
        'texte_info_vote', 'texte_mediatheque',
    ];

    public function index()
    {
        $parametres = Parametres::whereIn('cle', $this->allowed)
            ->orderByRaw("FIELD(cle, '" . implode("','", $this->allowed) . "')")
            ->get();

        return view('admin.parametres.index', compact('parametres'));
    }

    public function updateAll(Request $request)
    {
        $validated = $request->validate([
            'parametres' => 'nullable|array',
            'parametres.contact_telephone' => 'nullable|string|max:30',
            'parametres.contact_email' => 'nullable|email|max:150',
            'parametres.hero_titre' => 'nullable|string|max:255',
            'parametres.hero_sous_titre' => 'nullable|string|max:255',
            'parametres.texte_info_vote' => 'nullable|string|max:2000',
            'parametres.texte_mediatheque' => 'nullable|string|max:2000',
            'parametres.social_facebook' => 'nullable|url|max:255',
            'parametres.social_instagram' => 'nullable|url|max:255',
            'parametres.social_youtube' => 'nullable|url|max:255',
            'parametres.social_tiktok' => 'nullable|url|max:255',
        ], [
            'parametres.contact_email.email' => 'L\'email de contact doit être une adresse valide.',
            'parametres.contact_email.max' => 'L\'email de contact ne doit pas dépasser :max caractères.',
            'parametres.contact_telephone.max' => 'Le téléphone de contact ne doit pas dépasser :max caractères.',
            'parametres.hero_titre.max' => 'Le titre héros ne doit pas dépasser :max caractères.',
            'parametres.hero_sous_titre.max' => 'Le sous-titre héros ne doit pas dépasser :max caractères.',
            'parametres.texte_info_vote.max' => 'Le texte d\'information ovation ne doit pas dépasser :max caractères.',
            'parametres.texte_mediatheque.max' => 'La description médiathèque ne doit pas dépasser :max caractères.',
            'parametres.social_facebook.url' => 'Le lien Facebook doit être une URL valide.',
            'parametres.social_instagram.url' => 'Le lien Instagram doit être une URL valide.',
            'parametres.social_youtube.url' => 'Le lien YouTube doit être une URL valide.',
            'parametres.social_tiktok.url' => 'Le lien TikTok doit être une URL valide.',
        ]);

        $data = $validated['parametres'] ?? [];

        foreach ($data as $cle => $valeur) {
            if (in_array($cle, $this->allowed)) {
                Parametres::updateOrCreate(
                    ['cle' => $cle],
                    ['valeur' => trim($valeur ?? '')]
                );
            }
        }

        Parametre::flush();

        return to_route('admin.parametres.index')->with('success', 'Paramètres enregistrés.');
    }

    public function store(Request $request) { return to_route('admin.parametres.index'); }
    public function show(Parametres $parametre) { return to_route('admin.parametres.index'); }
    public function edit(Parametres $parametre) { return to_route('admin.parametres.index'); }
    public function update(Request $request, Parametres $parametre) { return to_route('admin.parametres.index'); }
    public function destroy(Parametres $parametre) { return to_route('admin.parametres.index'); }
}
