<?php

namespace Database\Seeders;

use App\Models\Tournament;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        // --- AKTIVNI TURNIRI ---

        Tournament::create([
            'user_id' => $user->id,
            'naziv' => 'Nacionalno takmičenje 2026',
            'datum_pocetka' => Carbon::now()->subDays(5),
            'datum_zavrsetka' => Carbon::now()->addDays(10),
            'broj_timova' => 12,
            'lokacija' => 'Beograd, Štark Arena',
        ]);

        Tournament::create([
            'user_id' => $user->id,
            'naziv' => 'FIBA World Cup',
            'datum_pocetka' => Carbon::now()->subDays(2),
            'datum_zavrsetka' => Carbon::now()->addDays(20),
            'broj_timova' => 64,
            'lokacija' => 'Manila, Filipini',
        ]);

        Tournament::create([
            'user_id' => $user->id,
            'naziv' => 'Evroliga Final Four',
            'datum_pocetka' => Carbon::now()->subDay(),
            'datum_zavrsetka' => Carbon::now()->addDays(3),
            'broj_timova' => 4,
            'lokacija' => 'Berlin, Uber Arena',
        ]);

        Tournament::create([
            'user_id' => $user->id,
            'naziv' => 'Letnja Liga Niš',
            'datum_pocetka' => Carbon::now(),
            'datum_zavrsetka' => Carbon::now()->addDays(7),
            'broj_timova' => 16,
            'lokacija' => 'Niš, Čair',
        ]);

        // --- ZAVRŠENI TURNIRI ---

        Tournament::create([
            'user_id' => $user->id,
            'naziv' => 'Zimska liga 2025',
            'datum_pocetka' => Carbon::now()->subMonths(2),
            'datum_zavrsetka' => Carbon::now()->subMonths(1),
            'broj_timova' => 8,
            'lokacija' => 'Novi Sad, SPENS',
        ]);

        Tournament::create([
            'user_id' => $user->id,
            'naziv' => 'Kup Radivoja Koraća',
            'datum_pocetka' => Carbon::now()->subMonths(3),
            'datum_zavrsetka' => Carbon::now()->subMonths(3)->addDays(4),
            'broj_timova' => 8,
            'lokacija' => 'Niš, Čair',
        ]);

        Tournament::create([
            'user_id' => $user->id,
            'naziv' => 'Turnir Prijateljstva',
            'datum_pocetka' => Carbon::now()->subYear(),
            'datum_zavrsetka' => Carbon::now()->subYear()->addDays(10),
            'broj_timova' => 10,
            'lokacija' => 'Kragujevac',
        ]);

        Tournament::create([
            'user_id' => $user->id,
            'naziv' => 'Memorijalni turnir 2024',
            'datum_pocetka' => Carbon::now()->subMonths(6),
            'datum_zavrsetka' => Carbon::now()->subMonths(6)->addDays(5),
            'broj_timova' => 12,
            'lokacija' => 'Čačak, Borac Arena',
        ]);
    }
}
