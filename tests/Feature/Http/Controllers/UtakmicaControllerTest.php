<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DomaciTim;
use App\Models\Referee;
use App\Models\StraniTim;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Utakmica;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UtakmicaController
 */
final class UtakmicaControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $utakmicas = Utakmica::factory()->count(3)->create();

        $response = $this->get(route('utakmicas.index'));

        $response->assertOk();
        $response->assertViewIs('utakmica.index');
        $response->assertViewHas('utakmicas', $utakmicas);
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('utakmicas.create'));

        $response->assertOk();
        $response->assertViewIs('utakmica.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UtakmicaController::class,
            'store',
            \App\Http\Requests\UtakmicaStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $tournament = Tournament::factory()->create();
        $domaci_tim = DomaciTim::factory()->create();
        $strani_tim = StraniTim::factory()->create();
        $referee = Referee::factory()->create();
        $mesto = fake()->word();
        $status = fake()->randomElement(/** enum_attributes **/);
        $user = User::factory()->create();

        $response = $this->post(route('utakmicas.store'), [
            'tournament_id' => $tournament->id,
            'domaci_tim_id' => $domaci_tim->id,
            'strani_tim_id' => $strani_tim->id,
            'referee_id' => $referee->id,
            'mesto' => $mesto,
            'status' => $status,
            'user_id' => $user->id,
        ]);

        $utakmicas = Utakmica::query()
            ->where('tournament_id', $tournament->id)
            ->where('domaci_tim_id', $domaci_tim->id)
            ->where('strani_tim_id', $strani_tim->id)
            ->where('referee_id', $referee->id)
            ->where('mesto', $mesto)
            ->where('status', $status)
            ->where('user_id', $user->id)
            ->get();
        $this->assertCount(1, $utakmicas);
        $utakmica = $utakmicas->first();

        $response->assertRedirect(route('utakmicas.index'));
        $response->assertSessionHas('utakmica.id', $utakmica->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $utakmica = Utakmica::factory()->create();

        $response = $this->get(route('utakmicas.show', $utakmica));

        $response->assertOk();
        $response->assertViewIs('utakmica.show');
        $response->assertViewHas('utakmica', $utakmica);
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $utakmica = Utakmica::factory()->create();

        $response = $this->get(route('utakmicas.edit', $utakmica));

        $response->assertOk();
        $response->assertViewIs('utakmica.edit');
        $response->assertViewHas('utakmica', $utakmica);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UtakmicaController::class,
            'update',
            \App\Http\Requests\UtakmicaUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $utakmica = Utakmica::factory()->create();
        $tournament = Tournament::factory()->create();
        $domaci_tim = DomaciTim::factory()->create();
        $strani_tim = StraniTim::factory()->create();
        $referee = Referee::factory()->create();
        $mesto = fake()->word();
        $status = fake()->randomElement(/** enum_attributes **/);
        $user = User::factory()->create();

        $response = $this->put(route('utakmicas.update', $utakmica), [
            'tournament_id' => $tournament->id,
            'domaci_tim_id' => $domaci_tim->id,
            'strani_tim_id' => $strani_tim->id,
            'referee_id' => $referee->id,
            'mesto' => $mesto,
            'status' => $status,
            'user_id' => $user->id,
        ]);

        $utakmica->refresh();

        $response->assertRedirect(route('utakmicas.index'));
        $response->assertSessionHas('utakmica.id', $utakmica->id);

        $this->assertEquals($tournament->id, $utakmica->tournament_id);
        $this->assertEquals($domaci_tim->id, $utakmica->domaci_tim_id);
        $this->assertEquals($strani_tim->id, $utakmica->strani_tim_id);
        $this->assertEquals($referee->id, $utakmica->referee_id);
        $this->assertEquals($mesto, $utakmica->mesto);
        $this->assertEquals($status, $utakmica->status);
        $this->assertEquals($user->id, $utakmica->user_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $utakmica = Utakmica::factory()->create();

        $response = $this->delete(route('utakmicas.destroy', $utakmica));

        $response->assertRedirect(route('utakmicas.index'));

        $this->assertModelMissing($utakmica);
    }
}
