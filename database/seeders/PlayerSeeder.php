<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tim = \App\Models\Team::first();

        if ($tim) {
            \App\Models\Player::create([
                'team_id' => $tim->id,
                'ime' => 'Bogdan',
                'prezime' => 'Bogdanović',
                'broj_dresa' => 13,
                'pozicija' => 'bek'
            ]);

            \App\Models\Player::create([
                'team_id' => $tim->id,
                'ime' => 'Nikola',
                'prezime' => 'Jokić',
                'broj_dresa' => 15,
                'pozicija' => 'centar'
            ]);
        }
    }
}
