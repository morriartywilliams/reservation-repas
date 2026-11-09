<?php

namespace Database\Factories;

use App\Models\Plat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plat>
 */
class PlatFactory extends Factory
{
    protected $model = Plat::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->randomElement([
                'Couscous aux légumes et poulet',
                'Chorba frik traditionnelle',
                'Tajine zitoune',
                'Loubia à la sauce tomate',
                'Salade composée et œufs durs',
                'Riz pilaf aux épices et veau',
                'Chakhchoukha de Biskra',
            ]),
            'description' => fake()->sentence(10),
        ];
    }
}
