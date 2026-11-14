<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSmokeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie que les écrans d'administration se rendent sans erreur serveur.
     */
    public function test_admin_pages_render(): void
    {
        $this->seed();
        $user = User::where('role', 'super_admin')->firstOrFail();

        $routes = [
            '/admin',
            '/admin/candidats',
            '/admin/candidats/create',
            '/admin/programmes',
            '/admin/programmes/create',
            '/admin/partenaires',
            '/admin/partenaires/create',
            '/admin/soutiens',
            '/admin/soutiens/create',
            '/admin/medias',
            '/admin/medias/create',
            '/admin/contacts',
            '/admin/votes',
            '/admin/votes/logs',
            '/admin/votes/export',
            '/admin/resultats',
            '/admin/parametres',
            '/admin/users',
            '/admin/users/create',
            '/admin/users/'.$user->id,
            '/profile',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);

            $this->assertLessThan(
                500,
                $response->baseResponse->getStatusCode(),
                "La page {$route} a retourné une erreur serveur."
            );
        }
    }
}
