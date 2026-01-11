<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'naziv' => fake()->regexify('[A-Za-z0-9]{255}'),
            'datum_pocetka' => fake()->dateTime(),
            'datum_zavrsetka' => fake()->dateTime(),
            'broj_timova' => fake()->numberBetween(-10000, 10000),
            'lokacija' => fake()->regexify('[A-Za-z0-9]{255}'),
        ];
    }
}
