<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Tournament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class TournamentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $tournaments = Tournament::factory()->count(3)->create();

        $response = $this->get(route('tournaments.index'));

        $response->assertOk();
        $response->assertViewIs('tournament.index');
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $naziv = $this->faker->sentence(3);
        $datum_pocetka = Carbon::now()->addDays(1);
        $datum_zavrsetka = Carbon::now()->addDays(5);
        $broj_timova = $this->faker->numberBetween(4, 32);
        $lokacija = $this->faker->city();

        $response = $this->post(route('tournaments.store'), [
            'user_id' => $user->id,
            'naziv' => $naziv,
            'datum_pocetka' => $datum_pocetka->toDateTimeString(),
            'datum_zavrsetka' => $datum_zavrsetka->toDateTimeString(),
            'broj_timova' => $broj_timova,
            'lokacija' => $lokacija,
        ]);

        $response->assertRedirect(route('tournaments.index'));

        $this->assertDatabaseHas('tournaments', [
            'naziv' => $naziv,
            'lokacija' => $lokacija,
            'broj_timova' => $broj_timova,
        ]);
    }

    #[Test]
    public function update_redirects(): void
    {
        $tournament = Tournament::factory()->create();
        $naziv = 'Novi Naziv Turnira';

        $response = $this->put(route('tournaments.update', $tournament), [
            'user_id' => User::factory()->create()->id,
            'naziv' => $naziv,
            'datum_pocetka' => Carbon::now()->toDateTimeString(),
            'datum_zavrsetka' => Carbon::now()->addMonth()->toDateTimeString(),
            'broj_timova' => 16,
            'lokacija' => 'Beograd',
        ]);

        $response->assertRedirect(route('tournaments.index'));
        $this->assertDatabaseHas('tournaments', ['naziv' => $naziv]);
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
