<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Utakmica;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class UtakmicaControllerTest extends TestCase
{
    // Izbacili smo AdditionalAssertions jer ti pravi error
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $utakmicas = Utakmica::factory()->count(3)->create();

        $response = $this->get(route('utakmicas.index'));

        $response->assertOk();
        $response->assertViewIs('utakmica.index');
        $response->assertViewHas('utakmicas');
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $tournament = Tournament::factory()->create();
        $domaci_tim = Team::factory()->create();
        $strani_tim = Team::factory()->create();

        // Sudija je User sa specifičnom ulogom (ovde kreiramo običnog usera jer testu treba samo ID)
        $referee = User::factory()->create(['role' => 'sudija']);
        $user = User::factory()->create(['role' => 'organizator']);

        $status = $this->faker->randomElement(['zakazano', 'u_toku', 'zavrseno']);
        $mesto = $this->faker->city();

        $response = $this->post(route('utakmicas.store'), [
            'tournament_id' => $tournament->id,
            'domaci_tim_id' => $domaci_tim->id,
            'strani_tim_id' => $strani_tim->id,
            'referee_id' => $referee->id, // Prosleđujemo ID usera koji je sudija
            'mesto' => $mesto,
            'status' => $status,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('utakmicas.index'));

        $this->assertDatabaseHas('utakmicas', [
            'mesto' => $mesto,
            'status' => $status,
            'referee_id' => $referee->id,
        ]);
    }

    #[Test]
    public function update_redirects(): void
    {
        $utakmica = Utakmica::factory()->create();
        $status = $this->faker->randomElement(['zakazano', 'u_toku', 'zavrseno']);

        $response = $this->put(route('utakmicas.update', $utakmica), [
            'tournament_id' => Tournament::factory()->create()->id,
            'domaci_tim_id' => Team::factory()->create()->id,
            'strani_tim_id' => Team::factory()->create()->id,
            'referee_id' => User::factory()->create()->id,
            'mesto' => 'Nova lokacija',
            'status' => $status,
            'user_id' => User::factory()->create()->id,
        ]);

        $response->assertRedirect(route('utakmicas.index'));

        $this->assertDatabaseHas('utakmicas', [
            'status' => $status,
            'mesto' => 'Nova lokacija',
        ]);
    }
}
