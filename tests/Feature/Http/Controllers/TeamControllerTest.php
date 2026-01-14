<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class TeamControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $teams = Team::factory()->count(3)->create();

        $response = $this->get(route('teams.index'));

        $response->assertOk();
        $response->assertViewIs('team.index');
        $response->assertViewHas('teams');
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $tournament = Tournament::factory()->create();
        $naziv = $this->faker->company();
        $grad = $this->faker->city();
        $broj_bodova = $this->faker->numberBetween(0, 100);

        $response = $this->post(route('teams.store'), [
            'user_id' => $user->id,
            'tournament_id' => $tournament->id,
            'naziv' => $naziv,
            'grad' => $grad,
            'broj_bodova' => $broj_bodova,
        ]);

        $response->assertRedirect(route('teams.index'));

        $this->assertDatabaseHas('teams', [
            'naziv' => $naziv,
            'grad' => $grad,
            'broj_bodova' => $broj_bodova,
        ]);
    }

    #[Test]
    public function update_redirects(): void
    {
        $team = Team::factory()->create();
        $novi_naziv = 'Novi Naziv Tima';

        $response = $this->put(route('teams.update', $team), [
            'user_id' => User::factory()->create()->id,
            'tournament_id' => Tournament::factory()->create()->id,
            'naziv' => $novi_naziv,
            'grad' => 'Beograd',
            'broj_bodova' => 10,
        ]);

        $response->assertRedirect(route('teams.index'));
        $this->assertDatabaseHas('teams', ['naziv' => $novi_naziv]);
    }

    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $team = Team::factory()->create();

        $response = $this->delete(route('teams.destroy', $team));

        $response->assertRedirect(route('teams.index'));
        $this->assertModelMissing($team);
    }
}
