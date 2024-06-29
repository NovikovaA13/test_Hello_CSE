<?php

namespace Database\Factories;

use App\Enums\StatutEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profil>
 */
class ProfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->name,
            'prenom' => fake()->lastName,
            'image' => $this->faker->image(public_path('/assets/images/'),
                300,
                200,
                null,
                false
            ),
            'statut' => fake()->randomElement(StatutEnum::cases())
        ];
    }
}
