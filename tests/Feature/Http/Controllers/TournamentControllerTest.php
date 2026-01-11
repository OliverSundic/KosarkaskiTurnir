<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Tournament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TournamentController
 */
final class TournamentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $tournaments = Tournament::factory()->count(3)->create();

        $response = $this->get(route('tournaments.index'));

        $response->assertOk();
        $response->assertViewIs('tournament.index');
        $response->assertViewHas('tournaments', $tournaments);
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('tournaments.create'));

        $response->assertOk();
        $response->assertViewIs('tournament.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TournamentController::class,
            'store',
            \App\Http\Requests\TournamentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $naziv = fake()->word();
        $datum_pocetka = Carbon::parse(fake()->dateTime());
        $datum_zavrsetka = Carbon::parse(fake()->dateTime());
        $broj_timova = fake()->numberBetween(-10000, 10000);
        $lokacija = fake()->word();

        $response = $this->post(route('tournaments.store'), [
            'user_id' => $user->id,
            'naziv' => $naziv,
            'datum_pocetka' => $datum_pocetka->toDateTimeString(),
            'datum_zavrsetka' => $datum_zavrsetka->toDateTimeString(),
            'broj_timova' => $broj_timova,
            'lokacija' => $lokacija,
        ]);

        $tournaments = Tournament::query()
            ->where('user_id', $user->id)
            ->where('naziv', $naziv)
            ->where('datum_pocetka', $datum_pocetka)
            ->where('datum_zavrsetka', $datum_zavrsetka)
            ->where('broj_timova', $broj_timova)
            ->where('lokacija', $lokacija)
            ->get();
        $this->assertCount(1, $tournaments);
        $tournament = $tournaments->first();

        $response->assertRedirect(route('tournaments.index'));
        $response->assertSessionHas('tournament.id', $tournament->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $tournament = Tournament::factory()->create();

        $response = $this->get(route('tournaments.show', $tournament));

        $response->assertOk();
        $response->assertViewIs('tournament.show');
        $response->assertViewHas('tournament', $tournament);
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $tournament = Tournament::factory()->create();

        $response = $this->get(route('tournaments.edit', $tournament));

        $response->assertOk();
        $response->assertViewIs('tournament.edit');
        $response->assertViewHas('tournament', $tournament);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TournamentController::class,
            'update',
            \App\Http\Requests\TournamentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $tournament = Tournament::factory()->create();
        $user = User::factory()->create();
        $naziv = fake()->word();
        $datum_pocetka = Carbon::parse(fake()->dateTime());
        $datum_zavrsetka = Carbon::parse(fake()->dateTime());
        $broj_timova = fake()->numberBetween(-10000, 10000);
        $lokacija = fake()->word();

        $response = $this->put(route('tournaments.update', $tournament), [
            'user_id' => $user->id,
            'naziv' => $naziv,
            'datum_pocetka' => $datum_pocetka->toDateTimeString(),
            'datum_zavrsetka' => $datum_zavrsetka->toDateTimeString(),
            'broj_timova' => $broj_timova,
            'lokacija' => $lokacija,
        ]);

        $tournament->refresh();

        $response->assertRedirect(route('tournaments.index'));
        $response->assertSessionHas('tournament.id', $tournament->id);

        $this->assertEquals($user->id, $tournament->user_id);
        $this->assertEquals($naziv, $tournament->naziv);
        $this->assertEquals($datum_pocetka, $tournament->datum_pocetka);
        $this->assertEquals($datum_zavrsetka, $tournament->datum_zavrsetka);
        $this->assertEquals($broj_timova, $tournament->broj_timova);
        $this->assertEquals($lokacija, $tournament->lokacija);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $tournament = Tournament::factory()->create();

        $response = $this->delete(route('tournaments.destroy', $tournament));

        $response->assertRedirect(route('tournaments.index'));

        $this->assertModelMissing($tournament);
    }
}
