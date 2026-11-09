<?php

namespace App\Policies;

use App\Models\Eleve;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function view(User $user, Eleve $eleve): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }

    public function create(User $user): bool
    {
        return $user->role?->nom === 'administrateur';
    }

    public function update(User $user, Eleve $eleve): bool
    {
        return $user->role?->nom === 'administrateur';
    }

    public function delete(User $user, Eleve $eleve): bool
    {
        return $user->role?->nom === 'administrateur';
    }
}
