<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Parametres::pluck('valeur', 'cle')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $rules = [
            'site_titre' => 'nullable|string|max:200',
            'site_description' => 'nullable|string|max:500',
            'date_debut_vote' => 'nullable|date',
            'date_fin_vote' => 'nullable|date|after_or_equal:date_debut_vote',
            'prix_du_vote' => 'nullable|integer|min:0',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'tiktok' => 'nullable|url|max:255',
            'afficher_compteur' => 'nullable|boolean',
            'email_notifications' => 'nullable|email|max:255',
        ];

        $request->validate($rules);

        foreach ($rules as $key => $rule) {
            if ($request->has($key)) {
                Parametres::updateOrCreate(
                    ['cle' => $key],
                    ['valeur' => $request->input($key, '')]
                );
            }
        }

        Cache::forget('site_settings');

        return to_route('admin.settings.index')->with('success', 'Paramètres enregistrés.');
    }
}
