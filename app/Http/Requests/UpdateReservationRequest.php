<?php

namespace App\Http\Requests;

use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $reservation = $this->route('reservation');
        return $this->user()?->can('update', $reservation) ?? false;
    }

    public function rules(): array
    {
        return [
            'repas_id' => ['required', 'exists:repas,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            if (! $user || ! $user->eleve) {
                $validator->errors()->add('eleve', 'Profil élève introuvable.');
                return;
            }

            $currentReservation = $this->route('reservation');
            $newRepasId = (int) $this->input('repas_id');

            if ($currentReservation && (int) $currentReservation->repas_id === $newRepasId) {
                return;
            }

            $newRepas = Repas::with('menu')->find($newRepasId);
            if (! $newRepas) {
                return;
            }

            // Target repas' menu must be publie
            if ($newRepas->menu?->statut !== 'publie') {
                $validator->errors()->add('repas_id', 'Le menu associé à ce repas doit être publié.');
            }

            // repas.statut = planifie
            if ($newRepas->statut !== 'planifie') {
                $validator->errors()->add('repas_id', 'Ce repas n\'est plus disponible pour réservation.');
            }

            // repas.date > today
            $today = now()->toDateString();
            $repasDate = $newRepas->date instanceof \DateTimeInterface
                ? $newRepas->date->format('Y-m-d')
                : (string) $newRepas->date;

            if ($repasDate <= $today) {
                $validator->errors()->add('repas_id', 'Les réservations pour le jour même ou des dates passées sont interdites.');
            }

            // Duplicate prevention on the new repas
            $alreadyBooked = Reservation::where('eleve_id', $user->eleve->id)
                ->where('repas_id', $newRepas->id)
                ->where('id', '!=', $currentReservation->id)
                ->exists();

            if ($alreadyBooked) {
                $validator->errors()->add('repas_id', 'Vous avez déjà une réservation pour ce repas.');
            }
        });
    }
}
