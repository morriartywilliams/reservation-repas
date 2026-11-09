<?php

namespace Database\Factories;

use App\Models\Eleve;
use App\Models\Repas;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'eleve_id' => Eleve::factory(),
            'repas_id' => Repas::factory(),
            'status' => 'confirmee',
            'attendance_status' => 'non_verifiee',
            'date_reservation' => now(),
        ];
    }

    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'attendance_status' => 'present',
        ]);
    }

    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'attendance_status' => 'absent',
        ]);
    }

    public function annulee(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'annulee',
        ]);
    }
}
