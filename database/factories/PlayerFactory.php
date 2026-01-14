<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'ime' => fake()->regexify('[A-Za-z0-9]{100}'),
            'prezime' => fake()->regexify('[A-Za-z0-9]{100}'),
            'broj_dresa' => fake()->numberBetween(-10000, 10000),
            'pozicija' => fake()->randomElement(['plejmejker', 'bek', 'krilo', 'krilni_centar', 'centar']),
        ];
    }
}
