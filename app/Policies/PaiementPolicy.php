<?php

namespace App\Policies;

use App\Models\Paiement;
use App\Models\User;

class PaiementPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function view(User $user, Paiement $paiement): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function update(User $user, Paiement $paiement): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }
}
