<?php

namespace Tests\Feature;

use App\Support\FedaPayInfos;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FedaPayInfosTest extends TestCase
{
    public static function operateurProvider(): array
    {
        return [
            ['mtn_open', 'MTN'],
            ['MTN_MOBILE', 'MTN'],
            ['mtn_open_gn', 'MTN'],
            ['moov', 'Moov'],
            ['Moov Money', 'Moov'],
            ['moov_tg', 'Moov'],
            ['sbin', 'Celtiis'],
            ['SBIN', 'Celtiis'],
            ['card', 'Carte'],
            ['visa', 'Carte'],
            ['mastercard', 'Carte'],
            ['weird_mode', 'Autre'],
            ['mobile_money', 'Autre'],
            [null, null],
            ['', null],
        ];
    }

    public static function paysProvider(): array
    {
        return [
            [null, null, null],
            ['BJ', null, 'BJ'],
            ['bj', '+22912345678', 'BJ'],
            ['GN', '+22412345678', 'GN'], // pays explicite prioritaire sur l'indicatif
            [null, '+22912345678', 'BJ'],
            [null, '+22890000000', 'TG'],
            [null, '+22507000000', 'CI'],
            [null, '+22670000000', 'BF'],
            [null, '+22790000000', 'NE'],
            [null, '+22177000000', 'SN'],
            [null, '+22462000000', 'GN'],
            [null, '+22370000000', 'ML'],
            [null, '+33612345678', null],
            [null, '22900000000', null], // sans « + » : indicatif non détecté
            [null, '', null],
        ];
    }

    public static function fraisProvider(): array
    {
        return [
            [['fees' => 75], 75],
            [['fees_amount' => 120], 120],
            [['fee' => 5], 5],
            [['fees' => '125.75'], 126],
            [['fees' => 0], 0],
            [['fees' => '0'], 0],
            [['amount' => 1000], null],
            [['fees' => ''], null],
            [['fees' => 'abc'], null],
            [[], null],
        ];
    }

    #[DataProvider('operateurProvider')]
    public function test_operateur(?string $mode, ?string $attendu): void
    {
        $this->assertSame($attendu, FedaPayInfos::operateur($mode));
    }

    #[DataProvider('paysProvider')]
    public function test_pays(?string $paysExplicite, ?string $telephone, ?string $attendu): void
    {
        $this->assertSame($attendu, FedaPayInfos::pays($paysExplicite, $telephone));
    }

    #[DataProvider('fraisProvider')]
    public function test_frais(array $data, ?int $attendu): void
    {
        $this->assertSame($attendu, FedaPayInfos::frais($data));
    }
}
