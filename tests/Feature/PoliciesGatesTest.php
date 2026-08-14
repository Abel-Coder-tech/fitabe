<?php

namespace Tests\Feature;

use App\Models\Candidats;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PoliciesGatesTest extends TestCase
{
    private function user(string $role): User
    {
        $u = new User(['name' => 'Test', 'email' => $role.'@test.local', 'role' => $role]);
        $u->id = 1;
        return $u;
    }

    private function candidat(): Candidats
    {
        $c = new Candidats(['id' => 7, 'nom' => 'Test', 'categorie' => 'Chant']);
        $c->id = 7;
        return $c;
    }

    public function test_gates_de_role(): void
    {
        $editor = $this->user('editor');
        $super = $this->user('super_admin');

        $this->assertTrue(Gate::forUser($super)->allows('admin'));
        $this->assertTrue(Gate::forUser($editor)->allows('admin'));
        $this->assertTrue(Gate::forUser($super)->allows('super_admin'));
        $this->assertFalse(Gate::forUser($editor)->allows('super_admin'));
    }

    public function test_politique_candidats(): void
    {
        $editor = $this->user('editor');
        $super = $this->user('super_admin');
        $c = $this->candidat();

        foreach (['viewAny', 'view', 'create', 'update'] as $ability) {
            $this->assertTrue(Gate::forUser($editor)->allows($ability, $c), "editor doit pouvoir $ability");
            $this->assertTrue(Gate::forUser($super)->allows($ability, $c), "super_admin doit pouvoir $ability");
        }

        $this->assertFalse(Gate::forUser($editor)->allows('delete', $c), 'editor ne doit pas supprimer');
        $this->assertFalse(Gate::forUser($editor)->allows('forceDelete', $c), 'editor ne doit pas forceDelete');
        $this->assertTrue(Gate::forUser($super)->allows('delete', $c), 'super_admin doit pouvoir supprimer');
        $this->assertTrue(Gate::forUser($super)->allows('forceDelete', $c), 'super_admin doit pouvoir forceDelete');
    }
}
