<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PlayerController
 */
final class PlayerControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $players = Player::factory()->count(3)->create();

        $response = $this->get(route('players.index'));

        $response->assertOk();
        $response->assertViewIs('player.index');
        $response->assertViewHas('players', $players);
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('players.create'));

        $response->assertOk();
        $response->assertViewIs('player.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PlayerController::class,
            'store',
            \App\Http\Requests\PlayerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $team = Team::factory()->create();
        $ime = fake()->word();
        $prezime = fake()->word();
        $broj_dresa = fake()->numberBetween(-10000, 10000);
        $pozicija = fake()->randomElement(/** enum_attributes **/);

        $response = $this->post(route('players.store'), [
            'team_id' => $team->id,
            'ime' => $ime,
            'prezime' => $prezime,
            'broj_dresa' => $broj_dresa,
            'pozicija' => $pozicija,
        ]);

        $players = Player::query()
            ->where('team_id', $team->id)
            ->where('ime', $ime)
            ->where('prezime', $prezime)
            ->where('broj_dresa', $broj_dresa)
            ->where('pozicija', $pozicija)
            ->get();
        $this->assertCount(1, $players);
        $player = $players->first();

        $response->assertRedirect(route('players.index'));
        $response->assertSessionHas('player.id', $player->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $player = Player::factory()->create();

        $response = $this->get(route('players.show', $player));

        $response->assertOk();
        $response->assertViewIs('player.show');
        $response->assertViewHas('player', $player);
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $player = Player::factory()->create();

        $response = $this->get(route('players.edit', $player));

        $response->assertOk();
        $response->assertViewIs('player.edit');
        $response->assertViewHas('player', $player);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PlayerController::class,
            'update',
            \App\Http\Requests\PlayerUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $player = Player::factory()->create();
        $team = Team::factory()->create();
        $ime = fake()->word();
        $prezime = fake()->word();
        $broj_dresa = fake()->numberBetween(-10000, 10000);
        $pozicija = fake()->randomElement(/** enum_attributes **/);

        $response = $this->put(route('players.update', $player), [
            'team_id' => $team->id,
            'ime' => $ime,
            'prezime' => $prezime,
            'broj_dresa' => $broj_dresa,
            'pozicija' => $pozicija,
        ]);

        $player->refresh();

        $response->assertRedirect(route('players.index'));
        $response->assertSessionHas('player.id', $player->id);

        $this->assertEquals($team->id, $player->team_id);
        $this->assertEquals($ime, $player->ime);
        $this->assertEquals($prezime, $player->prezime);
        $this->assertEquals($broj_dresa, $player->broj_dresa);
        $this->assertEquals($pozicija, $player->pozicija);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $player = Player::factory()->create();

        $response = $this->delete(route('players.destroy', $player));

        $response->assertRedirect(route('players.index'));

        $this->assertModelMissing($player);
    }
}
