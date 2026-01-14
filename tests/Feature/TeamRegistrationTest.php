<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TeamRegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_team_can_be_stored_in_database()
    {
        // 1. Moramo imati korisnika jer tvoja baza traži user_id u tabeli teams
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // 2. Kreiramo turnir
        $tournament = Tournament::factory()->create([
            'naziv' => 'Kup Srbije 2026',
        ]);

        // 3. Kreiramo tim sa SVIM potrebnim poljima (uključujući user_id)
        $team = Team::create([
            'tournament_id' => $tournament->id,
            'user_id' => $user->id, // OVO JE FALILO
            'naziv' => 'KK Borac Čačak',
            'grad' => 'Čačak',
            'broj_bodova' => 0,
        ]);

        // 4. Provera u bazi
        $this->assertDatabaseHas('teams', [
            'naziv' => 'KK Borac Čačak',
            'user_id' => $user->id,
            'tournament_id' => $tournament->id,
        ]);

        // 5. Provera relacije
        $this->assertEquals('Kup Srbije 2026', $team->tournament->naziv);
    }

    #[Test]
    public function tournament_can_have_multiple_teams()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $tournament = Tournament::factory()->create();

        // Kreiramo 3 tima povezana sa istim turnirom i korisnikom
        Team::factory()->count(3)->create([
            'tournament_id' => $tournament->id,
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $tournament->teams);
    }
}
