<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class PlayerControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $players = Player::factory()->count(3)->create();

        $response = $this->get(route('players.index'));

        $response->assertOk();
        $response->assertViewIs('player.index');
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $team = Team::factory()->create();
        $ime = $this->faker->firstName();
        $prezime = $this->faker->lastName();
        $broj_dresa = $this->faker->numberBetween(1, 99);
        $pozicija = $this->faker->randomElement(['PG', 'SG', 'SF', 'PF', 'C']);

        $response = $this->post(route('players.store'), [
            'team_id' => $team->id,
            'ime' => $ime,
            'prezime' => $prezime,
            'broj_dresa' => $broj_dresa,
            'pozicija' => $pozicija,
        ]);

        $response->assertRedirect(route('players.index'));

        $this->assertDatabaseHas('players', [
            'ime' => $ime,
            'prezime' => $prezime,
            'broj_dresa' => $broj_dresa,
            'pozicija' => $pozicija,
        ]);
    }

    #[Test]
    public function update_redirects(): void
    {
        $player = Player::factory()->create();
        $novo_ime = 'Nikola';

        $response = $this->put(route('players.update', $player), [
            'team_id' => Team::factory()->create()->id,
            'ime' => $novo_ime,
            'prezime' => 'Jokić',
            'broj_dresa' => 15,
            'pozicija' => 'C',
        ]);

        $response->assertRedirect(route('players.index'));
        $this->assertDatabaseHas('players', ['ime' => $novo_ime]);
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
