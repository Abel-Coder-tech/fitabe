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

    // Détermine le mode de vote : statut manuel prioritaire, sinon calculé depuis les dates
    public static function voteMode(): string
    {
        $statut = \App\Models\Parametres::where('cle', 'statut_vote')->value('valeur');

        if (in_array($statut, ['active', 'cloture', 'off'], true)) {
            return $statut;
        }

        $debut = \App\Models\Parametres::where('cle', 'date_debut_vote')->value('valeur');
        $fin = \App\Models\Parametres::where('cle', 'date_fin_vote')->value('valeur');

        if (!$debut || !$fin) {
            return 'off';
        }

        $now = \Carbon\Carbon::now();
        $d = \Carbon\Carbon::parse($debut);
        $f = \Carbon\Carbon::parse($fin);

        if ($now < $d) return 'off';
        if ($now >= $f) return 'cloture';

        return 'active';
    }
}
