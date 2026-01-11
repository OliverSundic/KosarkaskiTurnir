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
    // Kreiramo Organizatora
    \App\Models\User::factory()->create([
        'first_name' => 'Glavni',
        'last_name' => 'organizator',
        'email' => 'admin@raf.rs',
        'password' => bcrypt('password'),
        'role' => 'organizer',
    ]);

    // Kreiramo Sudiju
    \App\Models\User::factory()->create([
        'first_name' => 'Sudija',
        'last_name' => 'Marko',
        'email' => 'sudija@raf.rs',
        'password' => bcrypt('password'),
        'role' => 'referee',
    ]);

    // Ostali seederi
    $this->call([
        TournamentSeeder::class,
        TeamSeeder::class,
        PlayerSeeder::class,
        UtakmicaSeeder::class,
    ]);
}
}
