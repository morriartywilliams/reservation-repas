<?php

namespace Database\Factories;

use App\Models\Paiement;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paiement>
 */
class PaiementFactory extends Factory
{
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'montant' => 200.00,
            'statut' => 'impaye',
            'moyen' => null,
            'date' => null,
            'recorded_by' => User::factory()->admin(),
        ];
    }

    public function paye(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'paye',
            'moyen' => 'especes',
            'date' => now()->toDateString(),
        ]);
    }

    public function exonere(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'exonere',
            'moyen' => null,
            'date' => now()->toDateString(),
        ]);
    }
}
