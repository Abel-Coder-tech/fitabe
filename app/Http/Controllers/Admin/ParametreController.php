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
        $data = $request->input('parametres', []);

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
