<?php

namespace App\Policies;

use App\Models\Plat;
use App\Models\User;

class PlatPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function view(User $user, Plat $plat): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function update(User $user, Plat $plat): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function delete(User $user, Plat $plat): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }
}
