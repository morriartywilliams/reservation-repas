<?php

namespace App\Services;

use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    /**
     * Bulk-update attendance_status for reservations tied to a repas.
     *
     * @param int $repasId
     * @param array<int, array{reservation_id: int, attendance_status: string}> $entries
     * @return int Number of updated records
     * @throws ValidationException
     */
    public function validate(int $repasId, array $entries): int
    {
        $repas = Repas::findOrFail($repasId);

        if ($repas->statut !== 'planifie') {
            throw ValidationException::withMessages([
                'repas_id' => 'Les présences ne peuvent plus être modifiées car ce repas est déjà marqué comme servi.',
            ]);
        }

        $updatedCount = 0;

        DB::transaction(function () use ($repas, $entries, &$updatedCount) {
            foreach ($entries as $entry) {
                $reservation = Reservation::where('id', $entry['reservation_id'])
                    ->where('repas_id', $repas->id)
                    ->where('status', 'confirmee')
                    ->first();

                if ($reservation) {
                    $reservation->update([
                        'attendance_status' => $entry['attendance_status'],
                    ]);
                    $updatedCount++;
                }
            }
        });

        return $updatedCount;
    }
}
