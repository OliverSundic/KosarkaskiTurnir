<?php

namespace Database\Factories;

use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tournament_id' => Tournament::factory(),
            'naziv' => fake()->regexify('[A-Za-z0-9]{255}'),
            'grad' => fake()->regexify('[A-Za-z0-9]{100}'),
            'broj_bodova' => fake()->numberBetween(-10000, 10000),
        ];
    }
}
