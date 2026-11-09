<?php

namespace Database\Factories;

use App\Models\Eleve;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Eleve>
 */
class EleveFactory extends Factory
{
    protected $model = Eleve::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->eleve(),
            'matricule' => 'MAT-' . fake()->unique()->numerify('#####'),
            'classe' => fake()->randomElement(['1AS-1', '1AS-2', '2AS-Scientifique', '2AS-Lettres', '3AS-Math']),
            'date_naissance' => fake()->dateTimeBetween('-18 years', '-10 years')->format('Y-m-d'),
        ];
    }
}
