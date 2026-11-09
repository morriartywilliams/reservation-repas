<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Repas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Repas>
 */
class RepasFactory extends Factory
{
    protected $model = Repas::class;

    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'date' => fake()->dateTimeBetween('+1 days', '+7 days')->format('Y-m-d'),
            'statut' => 'planifie',
        ];
    }

    public function servi(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'servi',
        ]);
    }
}
