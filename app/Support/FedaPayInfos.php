<?php

namespace App\Support;

/**
 * Extraction des informations d'un webhook FedaPay :
 * opérateur de mobile money, pays et frais.
 */
class FedaPayInfos
{
    private const PAYS_PAR_INDICATIF = [
        '+229' => 'BJ',
        '+228' => 'TG',
        '+225' => 'CI',
        '+226' => 'BF',
        '+227' => 'NE',
        '+221' => 'SN',
        '+224' => 'GN',
        '+223' => 'ML',
    ];

    /**
     * Normalise le mode de paiement FedaPay en opérateur affichable.
     * Modes FedaPay : mtn_open, mtn_ci, mtn_open_gn, moov, moov_tg, sbin (Celtiis), card, …
     */
    public static function operateur(?string $mode): ?string
    {
        if (! $mode) {
            return null;
        }

        $m = strtolower($mode);

        if (str_contains($m, 'mtn')) {
            return 'MTN';
        }
        if (str_contains($m, 'moov')) {
            return 'Moov';
        }
        if (str_contains($m, 'sbin') || str_contains($m, 'celtis')) {
            return 'Celtiis';
        }
        if (str_contains($m, 'card') || $m === 'visa' || $m === 'mastercard') {
            return 'Carte';
        }

        return 'Autre';
    }

    /**
     * Code pays ISO 3166-1 alpha-2. Priorité au champ pays fourni
     * par FedaPay (customer.country), sinon déduit de l'indicatif du téléphone.
     */
    public static function pays(?string $country, ?string $phone): ?string
    {
        $country = $country ? strtoupper(trim($country)) : null;

        if ($country && strlen($country) === 2) {
            return $country;
        }

        if (! $phone) {
            return null;
        }

        $normalise = preg_replace('/[^0-9+]/', '', $phone);

        foreach (self::PAYS_PAR_INDICATIF as $indicatif => $code) {
            if (str_starts_with($normalise, $indicatif)) {
                return $code;
            }
        }

        return null;
    }

    /**
     * Frais prélevés par l'agrégateur, en FCFA. Renvoie null si absents du webhook.
     */
    public static function frais(array $data): ?int
    {
        foreach (['fees', 'fees_amount', 'fee'] as $cle) {
            if (isset($data[$cle]) && is_numeric($data[$cle])) {
                return (int) round((float) $data[$cle]);
            }
        }

        return null;
    }
}
