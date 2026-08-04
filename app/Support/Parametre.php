<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class Parametre
{
    public static function get(string $cle, string $defaut = ''): string
    {
        $params = self::all();
        return $params[$cle] ?? $defaut;
    }

    public static function getInt(string $cle, int $defaut = 0): int
    {
        return (int) self::get($cle, (string) $defaut);
    }

    public static function all(): array
    {
        return Cache::remember('parametres', 3600, function () {
            $rows = \App\Models\Parametres::pluck('valeur', 'cle')->toArray();
            return $rows;
        });
    }

    public static function flush(): void
    {
        Cache::forget('parametres');
    }

    // Détermine le mode de vote : clôture manuelle prioritaire, sinon
    // fenêtre effective (début manuel éventuel sinon dates planifiées) avec arrêt auto.
    public static function voteMode(): string
    {
        $statut = \App\Models\Parametres::where('cle', 'statut_vote')->value('valeur');
        [$debutReel, $finReelle] = static::fenetreEffective();

        if (!$debutReel || !$finReelle) {
            return in_array($statut, ['active', 'cloture', 'off'], true) ? $statut : 'off';
        }

        $now = \Carbon\Carbon::now();

        // Clôture manuelle prioritaire (le bouton « Clôturer » gagne jusqu'à relance)
        if ($statut === 'cloture') {
            return 'cloture';
        }
        if ($statut === 'off') {
            return 'off';
        }

        // Démarrage manuel : reste actif, mais s'arrête tout seul à la fin effective
        if ($statut === 'active') {
            return $now->gte(\Carbon\Carbon::parse($finReelle)) ? 'cloture' : 'active';
        }

        // Mode automatique (aucune action manuelle) : ouverture à l'heure prévue, arrêt à la fin
        if ($now->lt(\Carbon\Carbon::parse($debutReel))) {
            return 'off';
        }
        if ($now->gte(\Carbon\Carbon::parse($finReelle))) {
            return 'cloture';
        }

        return 'active';
    }

    // Fenêtre effective : si un démarrage manuel a été enregistré (debut_effectif),
    // il devient le début et la durée planifiée est reportée ; sinon les dates planifiées.
    // Retourne [debutReel|null, finReelle|null].
    public static function fenetreEffective(): array
    {
        $debut = \App\Models\Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $fin = \App\Models\Parametres::where('cle', 'date_fin_vote')->value('valeur');

        if (!$debut || !$fin) {
            return [null, null];
        }

        $debutPlanne = \Carbon\Carbon::parse($debut);
        $finPlannee = \Carbon\Carbon::parse($fin);
        $debutManuel = \App\Models\Parametres::where('cle', 'debut_effectif')->value('valeur');

        if ($debutManuel) {
            $debutReel = \Carbon\Carbon::parse($debutManuel);
            $duree = max(0, $debutPlanne->diffInSeconds($finPlannee));
            $finReelle = $debutReel->copy()->addSeconds($duree);

            return [$debutReel->toDateTimeString(), $finReelle->toDateTimeString()];
        }

        return [$debutPlanne->toDateTimeString(), $finPlannee->toDateTimeString()];
    }

    // Début effectif des ovations (démarrage manuel sinon début planifié).
    public static function debutEffectif(): ?string
    {
        $manuel = \App\Models\Parametres::where('cle', 'debut_effectif')->value('valeur');

        return $manuel ?: \App\Models\Parametres::where('cle', 'date_debut_vote')->value('valeur') ?: null;
    }

    // Fin effective des ovations (fin planifiée décalée si démarrage manuel).
    public static function finEffective(): ?string
    {
        return static::fenetreEffective()[1];
    }
}
