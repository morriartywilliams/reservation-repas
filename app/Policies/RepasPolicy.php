<?php

namespace App\Policies;

use App\Models\Repas;
use App\Models\User;

class RepasPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function view(User $user, Repas $repas): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function markServed(User $user, Repas $repas): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }
}
