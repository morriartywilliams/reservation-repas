<?php

namespace App\Http\Requests;

use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;

class ValidateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('validateAttendance', Reservation::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'repas_id' => ['required', 'exists:repas,id'],
            'entries' => ['required', 'array', 'min:1'],
            'entries.*.reservation_id' => ['required', 'exists:reservations,id'],
            'entries.*.attendance_status' => ['required', 'in:present,absent'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $repasId = $this->integer('repas_id');
            if (! $repasId || ! Repas::whereKey($repasId)->exists()) {
                return;
            }

            $entries = $this->input('entries', []);
            $reservationIds = collect($entries)->pluck('reservation_id')->filter()->map(fn ($id) => (int) $id);

            if ($reservationIds->count() !== $reservationIds->unique()->count()) {
                $validator->errors()->add('entries', 'Chaque rÃ©servation ne peut Ãªtre validÃ©e qu\'une seule fois.');
            }

            $validCount = Reservation::where('repas_id', $repasId)
                ->where('status', 'confirmee')
                ->whereIn('id', $reservationIds->all())
                ->count();

            if ($validCount !== $reservationIds->unique()->count()) {
                $validator->errors()->add('entries', 'Toutes les rÃ©servations doivent Ãªtre confirmÃ©es et appartenir au repas sÃ©lectionnÃ©.');
            }
        });
    }
}
