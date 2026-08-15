<?php

namespace Tests\Feature;

use App\Models\Candidats;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidatsPhotoUrlTest extends TestCase
{
    public function test_sans_photo_retourne_fallback(): void
    {
        $c = new Candidats();
        $this->assertSame(asset('images/hero.jpg'), $c->photo_url);
    }

    public function test_fichier_manquant_retourne_fallback(): void
    {
        $c = new Candidats(['photo' => 'photos/introuvable.jpg']);
        $this->assertSame(asset('images/hero.jpg'), $c->photo_url);
    }

    public function test_normalise_chemin_et_prefixe(): void
    {
        Storage::disk('public')->put('photos/__test_photo_url.jpg', 'x');
        try {
            $antislash = new Candidats(['photo' => 'photos\\__test_photo_url.jpg']);
            $this->assertStringContainsString('/storage/photos/__test_photo_url.jpg', $antislash->photo_url);

            $prefixe = new Candidats(['photo' => '/storage/photos/__test_photo_url.jpg']);
            $this->assertStringContainsString('/storage/photos/__test_photo_url.jpg', $prefixe->photo_url);
        } finally {
            Storage::disk('public')->delete('photos/__test_photo_url.jpg');
        }
    }

    public function test_photo_url_incluse_dans_serialisation(): void
    {
        $c = new Candidats(['photo' => 'photos/introuvable.jpg']);
        $json = json_decode($c->toJson(), true);
        $this->assertArrayHasKey('photo_url', $json);
    }
}
