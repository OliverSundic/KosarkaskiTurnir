<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Utakmica;
use Illuminate\Database\Seeder;

class UtakmicaSeeder extends Seeder
{
    public function run(): void
    {
        $turnir = Tournament::first();
        $timovi = Team::take(2)->get();
        $sudija = User::where('role', 'referee')->first();
        $organizator = User::where('role', 'organizer')->first();

        // Proveravamo da li imamo sve potrebne podatke pre insertovanja
        if ($turnir && $timovi->count() >= 2 && $sudija && $organizator) {
            Utakmica::create([
                'tournament_id' => $turnir->id,
                'user_id' => $organizator->id, // OVO JE FALILO
                'domaci_tim_id' => $timovi[0]->id,
                'strani_tim_id' => $timovi[1]->id,
                'referee_id' => $sudija->id,
                'mesto' => 'Štark Arena',
                'rezultat' => '0:0',
                'status' => 'zakazana',
            ]);
        }
    }
}
