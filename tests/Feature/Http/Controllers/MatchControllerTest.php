<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DomaciTim;
use App\Models\Match;
use App\Models\Referee;
use App\Models\StraniTim;
use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\MatchController
 */
final class MatchControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $matches = Match::factory()->count(3)->create();

        $response = $this->get(route('matches.index'));

        $response->assertOk();
        $response->assertViewIs('match.index');
        $response->assertViewHas('matches', $matches);
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('matches.create'));

        $response->assertOk();
        $response->assertViewIs('match.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MatchController::class,
            'store',
            \App\Http\Requests\MatchStoreRequest::class
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

        $response = $this->post(route('matches.store'), [
            'tournament_id' => $tournament->id,
            'domaci_tim_id' => $domaci_tim->id,
            'strani_tim_id' => $strani_tim->id,
            'referee_id' => $referee->id,
            'mesto' => $mesto,
            'status' => $status,
        ]);

        $matches = Match::query()
            ->where('tournament_id', $tournament->id)
            ->where('domaci_tim_id', $domaci_tim->id)
            ->where('strani_tim_id', $strani_tim->id)
            ->where('referee_id', $referee->id)
            ->where('mesto', $mesto)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $matches);
        $match = $matches->first();

        $response->assertRedirect(route('matches.index'));
        $response->assertSessionHas('match.id', $match->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $match = Match::factory()->create();

        $response = $this->get(route('matches.show', $match));

        $response->assertOk();
        $response->assertViewIs('match.show');
        $response->assertViewHas('match', $match);
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $match = Match::factory()->create();

        $response = $this->get(route('matches.edit', $match));

        $response->assertOk();
        $response->assertViewIs('match.edit');
        $response->assertViewHas('match', $match);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MatchController::class,
            'update',
            \App\Http\Requests\MatchUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $match = Match::factory()->create();
        $tournament = Tournament::factory()->create();
        $domaci_tim = DomaciTim::factory()->create();
        $strani_tim = StraniTim::factory()->create();
        $referee = Referee::factory()->create();
        $mesto = fake()->word();
        $status = fake()->randomElement(/** enum_attributes **/);

        $response = $this->put(route('matches.update', $match), [
            'tournament_id' => $tournament->id,
            'domaci_tim_id' => $domaci_tim->id,
            'strani_tim_id' => $strani_tim->id,
            'referee_id' => $referee->id,
            'mesto' => $mesto,
            'status' => $status,
        ]);

        $match->refresh();

        $response->assertRedirect(route('matches.index'));
        $response->assertSessionHas('match.id', $match->id);

        $this->assertEquals($tournament->id, $match->tournament_id);
        $this->assertEquals($domaci_tim->id, $match->domaci_tim_id);
        $this->assertEquals($strani_tim->id, $match->strani_tim_id);
        $this->assertEquals($referee->id, $match->referee_id);
        $this->assertEquals($mesto, $match->mesto);
        $this->assertEquals($status, $match->status);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $match = Match::factory()->create();

        $response = $this->delete(route('matches.destroy', $match));

        $response->assertRedirect(route('matches.index'));

        $this->assertModelMissing($match);
    }
}
