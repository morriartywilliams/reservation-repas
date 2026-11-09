<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reservation $reservation): bool
    {
        if (in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true)) {
            return true;
        }

        if ($user->role?->nom === 'eleve' && $user->eleve) {
            return $reservation->eleve_id === $user->eleve->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role?->nom === 'eleve' && $user->eleve !== null;
    }

    public function update(User $user, Reservation $reservation): bool
    {
        if ($user->role?->nom !== 'eleve' || ! $user->eleve || $reservation->eleve_id !== $user->eleve->id) {
            return false;
        }

        // Must be within edit window: repas.statut = 'planifie' and repas.date > today
        $repas = $reservation->repas;
        if (! $repas) {
            return false;
        }

        $today = now()->toDateString();
        $repasDate = $repas->date instanceof \DateTimeInterface
            ? $repas->date->format('Y-m-d')
            : (string) $repas->date;

        return $repas->statut === 'planifie' && $repasDate > $today;
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->update($user, $reservation);
    }

    public function validateAttendance(User $user): bool
    {
        return in_array($user->role?->nom, ['administrateur', 'gestionnaire'], true);
    }
}
