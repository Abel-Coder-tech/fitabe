<?php

namespace App\Providers;

use App\Support\Parametre;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('fr');
        Paginator::useBootstrapFive();

        View::share('site', [
            'edition_nom' => Parametre::get('edition_nom', 'FITAB 2026'),
            'edition_lieu' => Parametre::get('edition_lieu', 'Porto-Novo, Bénin'),
            'edition_date_debut' => Parametre::get('edition_date_debut'),
            'edition_date_fin' => Parametre::get('edition_date_fin'),
            'logo_url' => Parametre::get('logo_url'),
            'contact_telephone' => Parametre::get('contact_telephone'),
            'contact_email' => Parametre::get('contact_email'),
            'social_facebook' => Parametre::get('social_facebook'),
            'social_instagram' => Parametre::get('social_instagram'),
            'social_youtube' => Parametre::get('social_youtube'),
            'social_tiktok' => Parametre::get('social_tiktok'),
            'hero_titre' => Parametre::get('hero_titre'),
            'hero_sous_titre' => Parametre::get('hero_sous_titre'),
            'prix_ovation' => Parametre::getInt('prix_ovation', 100),
            'devise' => Parametre::get('devise', 'FCFA'),
            'texte_info_vote' => Parametre::get('texte_info_vote'),
            'texte_mediatheque' => Parametre::get('texte_mediatheque'),
        ]);
    }
}
