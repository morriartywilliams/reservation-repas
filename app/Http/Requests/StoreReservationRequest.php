<?php

namespace App\Http\Requests;

use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Reservation::class) ?? false;
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
                $validator->errors()->add('eleve', 'Profil élève introuvable pour cet utilisateur.');
                return;
            }

            $repasId = $this->input('repas_id');
            if (! $repasId) {
                return;
            }

            $repas = Repas::with('menu')->find($repasId);
            if (! $repas) {
                return;
            }

            // Target repas' menu must be publie
            if ($repas->menu?->statut !== 'publie') {
                $validator->errors()->add('repas_id', 'Le menu associé à ce repas doit être publié.');
            }

            // repas.statut = planifie
            if ($repas->statut !== 'planifie') {
                $validator->errors()->add('repas_id', 'Ce repas n\'est plus disponible pour réservation.');
            }

            // repas.date > today (same-day and past-day booking is blocked)
            $today = now()->toDateString();
            $repasDate = $repas->date instanceof \DateTimeInterface
                ? $repas->date->format('Y-m-d')
                : (string) $repas->date;

            if ($repasDate <= $today) {
                $validator->errors()->add('repas_id', 'Les réservations pour le jour même ou des dates passées sont interdites.');
            }

            // Duplicate prevention: UNIQUE(eleve_id, repas_id)
            $alreadyBooked = Reservation::where('eleve_id', $user->eleve->id)
                ->where('repas_id', $repas->id)
                ->exists();

            if ($alreadyBooked) {
                $validator->errors()->add('repas_id', 'Vous avez déjà une réservation pour ce repas.');
            }
        });
    }
}
