<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TeamController
 */
final class TeamControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $teams = Team::factory()->count(3)->create();

        $response = $this->get(route('teams.index'));

        $response->assertOk();
        $response->assertViewIs('team.index');
        $response->assertViewHas('teams', $teams);
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('teams.create'));

        $response->assertOk();
        $response->assertViewIs('team.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeamController::class,
            'store',
            \App\Http\Requests\TeamStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $tournament = Tournament::factory()->create();
        $naziv = fake()->word();
        $grad = fake()->word();
        $broj_bodova = fake()->numberBetween(-10000, 10000);

        $response = $this->post(route('teams.store'), [
            'user_id' => $user->id,
            'tournament_id' => $tournament->id,
            'naziv' => $naziv,
            'grad' => $grad,
            'broj_bodova' => $broj_bodova,
        ]);

        $teams = Team::query()
            ->where('user_id', $user->id)
            ->where('tournament_id', $tournament->id)
            ->where('naziv', $naziv)
            ->where('grad', $grad)
            ->where('broj_bodova', $broj_bodova)
            ->get();
        $this->assertCount(1, $teams);
        $team = $teams->first();

        $response->assertRedirect(route('teams.index'));
        $response->assertSessionHas('team.id', $team->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $team = Team::factory()->create();

        $response = $this->get(route('teams.show', $team));

        $response->assertOk();
        $response->assertViewIs('team.show');
        $response->assertViewHas('team', $team);
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $team = Team::factory()->create();

        $response = $this->get(route('teams.edit', $team));

        $response->assertOk();
        $response->assertViewIs('team.edit');
        $response->assertViewHas('team', $team);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeamController::class,
            'update',
            \App\Http\Requests\TeamUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $tournament = Tournament::factory()->create();
        $naziv = fake()->word();
        $grad = fake()->word();
        $broj_bodova = fake()->numberBetween(-10000, 10000);

        $response = $this->put(route('teams.update', $team), [
            'user_id' => $user->id,
            'tournament_id' => $tournament->id,
            'naziv' => $naziv,
            'grad' => $grad,
            'broj_bodova' => $broj_bodova,
        ]);

        $team->refresh();

        $response->assertRedirect(route('teams.index'));
        $response->assertSessionHas('team.id', $team->id);

        $this->assertEquals($user->id, $team->user_id);
        $this->assertEquals($tournament->id, $team->tournament_id);
        $this->assertEquals($naziv, $team->naziv);
        $this->assertEquals($grad, $team->grad);
        $this->assertEquals($broj_bodova, $team->broj_bodova);
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
