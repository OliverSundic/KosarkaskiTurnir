<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UtakmicaFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tournament_id' => Tournament::factory(),
            'domaci_tim_id' => Team::factory(),
            'strani_tim_id' => Team::factory(),
            'referee_id' => User::factory(),
            'mesto' => fake()->regexify('[A-Za-z0-9]{255}'),
            'rezultat' => fake()->regexify('[A-Za-z0-9]{20}'),
            'status' => fake()->randomElement(["zakazana","u_toku","zavrsena","otkazana"]),
            'user_id' => User::factory(),
        ];
    }
}
