<?php

namespace App\Policies;

use App\Models\Candidats;
use App\Models\User;

class CandidatPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Candidats $candidat): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Candidats $candidat): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Candidats $candidat): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, Candidats $candidat): bool
    {
        return $user->isSuperAdmin();
    }
}
