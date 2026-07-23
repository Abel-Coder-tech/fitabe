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

    private array $labels = [
        'contact_telephone' => 'Contact',
        'contact_email' => 'Email',
        'social_facebook' => 'Facebook',
        'social_instagram' => 'Instagram',
        'social_youtube' => 'YouTube',
        'social_tiktok' => 'TikTok',
        'hero_titre' => 'Titre héros',
        'hero_sous_titre' => 'Sous-titre héros',
        'texte_info_vote' => 'Info ovation',
        'texte_mediatheque' => 'Description médiathèque',
    ];

    public function index()
    {
        $parametres = Parametres::whereIn('cle', $this->allowed)
            ->orderByRaw("FIELD(cle, '" . implode("','", $this->allowed) . "')")
            ->get();

        return view('admin.parametres.index', compact('parametres'));
    }

    public function create()
    {
        return to_route('admin.parametres.index');
    }

    public function store(Request $request)
    {
        return to_route('admin.parametres.index');
    }

    public function show(Parametres $parametre)
    {
        return to_route('admin.parametres.edit', $parametre);
    }

    public function edit(Parametres $parametre)
    {
        abort_unless(in_array($parametre->cle, $this->allowed), 404);

        $label = $this->labels[$parametre->cle] ?? $parametre->cle;

        return view('admin.parametres.edit', compact('parametre', 'label'));
    }

    public function update(Request $request, Parametres $parametre)
    {
        abort_unless(in_array($parametre->cle, $this->allowed), 404);

        $validated = $request->validate([
            'valeur' => 'nullable|string',
        ]);

        if ($request->hasFile('valeur_file') && $request->file('valeur_file')->isValid()) {
            $validated['valeur'] = $request->file('valeur_file')->store('logos', 'public');
        }

        $parametre->update($validated);
        Parametre::flush();

        return to_route('admin.parametres.index')->with('success', 'Paramètre mis à jour.');
    }

    public function destroy(Parametres $parametre)
    {
        abort_unless(in_array($parametre->cle, $this->allowed), 404);

        $parametre->forceDelete();
        Parametre::flush();

        return to_route('admin.parametres.index')->with('success', 'Paramètre supprimé.');
    }
}
