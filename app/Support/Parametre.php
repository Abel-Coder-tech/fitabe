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
}
