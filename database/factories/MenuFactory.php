<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 days', '+10 days');
        $endDate = (clone $startDate)->modify('+4 days');

        return [
            'nom' => 'Menu Semaine du ' . $startDate->format('d/m/Y'),
            'date_debut' => $startDate->format('Y-m-d'),
            'date_fin' => $endDate->format('Y-m-d'),
            'statut' => 'brouillon',
            'created_by' => User::factory()->admin(),
        ];
    }

    public function publie(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'publie',
        ]);
    }
}
