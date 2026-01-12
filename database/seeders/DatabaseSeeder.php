<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Korisnici
        \App\Models\User::create([
            'first_name' => 'Glavni',
            'last_name' => 'Organizator',
            'email' => 'admin@raf.rs',
            'password' => bcrypt('password'),
            'role' => 'organizer',
        ]);

        \App\Models\User::create([
            'first_name' => 'Sudija',
            'last_name' => 'Marko',
            'email' => 'sudija@raf.rs',
            'password' => bcrypt('password'),
            'role' => 'referee',
        ]);

        // Redosled je bitan: prvo turnir, pa timovi, pa igrači
        $this->call([
            TournamentSeeder::class,
            TeamSeeder::class,
            PlayerSeeder::class,
            UtakmicaSeeder::class,
        ]);
    }
}
