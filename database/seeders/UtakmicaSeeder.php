<?php

namespace Database\Seeders;

use App\Models\Utakmica;
use Illuminate\Database\Seeder;

class UtakmicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Utakmica::factory()->count(5)->create();
    }
}
