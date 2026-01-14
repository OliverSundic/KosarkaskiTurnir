<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Utakmica;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function leaderboard_correctly_calculates_points_and_displays_winner()
    {
        /** @var User $user */
        $user = User::factory()->create();

        // 1. Kreiramo turnir
        $tournament = Tournament::factory()->create();

        // 2. Kreiramo timove DIREKTNO vezane za ovaj turnir
        $teamA = Team::factory()->create([
            'naziv' => 'Partizan',
            'tournament_id' => $tournament->id,
        ]);
        $teamB = Team::factory()->create([
            'naziv' => 'Zvezda',
            'tournament_id' => $tournament->id,
        ]);

        // 3. Kreiramo utakmicu
        Utakmica::create([
            'tournament_id' => $tournament->id,
            'domaci_tim_id' => $teamA->id,
            'strani_tim_id' => $teamB->id,
            'rezultat' => '100:80',
            'status' => 'zavrseno',
            'kolo' => 1,
            'mesto' => 'Hala Sportova',
            'user_id' => $user->id,
        ]);

        // 4. Pozivamo rutu
        $response = $this->actingAs($user)->get(route('tournaments.leaderboard', $tournament->id));

        // 5. Provere
        $response->assertStatus(200);

        // Sada će assertSee raditi jer timovi pripadaju turniru
        $response->assertSee('Partizan');
        $response->assertSee('Zvezda');
        $response->assertSee('2'); // Bodovi za Partizan
    }
}
