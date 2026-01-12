<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $turnir = \App\Models\Tournament::first();
        $user = \App\Models\User::first();

        $timovi = ['Partizan', 'Crvena Zvezda', 'Real Madrid', 'Monako'];

        foreach ($timovi as $ime) {
            \App\Models\Team::create([
                'user_id' => $user->id,
                'tournament_id' => $turnir->id,
                'naziv' => $ime,
                'grad' => 'Beograd',
                'broj_bodova' => 0
            ]);
        }
    }
}
