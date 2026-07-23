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
            'contact_telephone' => Parametre::get('contact_telephone'),
            'contact_email' => Parametre::get('contact_email'),
            'social_facebook' => Parametre::get('social_facebook'),
            'social_instagram' => Parametre::get('social_instagram'),
            'social_youtube' => Parametre::get('social_youtube'),
            'social_tiktok' => Parametre::get('social_tiktok'),
            'hero_titre' => Parametre::get('hero_titre', 'FITAB 2026'),
            'hero_sous_titre' => Parametre::get('hero_sous_titre'),
            'texte_info_vote' => Parametre::get('texte_info_vote'),
            'texte_mediatheque' => Parametre::get('texte_mediatheque'),
            'edition_nom' => Parametre::get('hero_titre', 'FITAB 2026'),
            'edition_lieu' => 'Porto-Novo, Bénin',
            'prix_ovation' => 100,
            'devise' => 'FCFA',
        ]);
    }
}
