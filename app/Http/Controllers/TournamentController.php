<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Utakmica;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    // U metodi za Dashboard (verovatno index)
    public function index()
    {
        $danas = now();

        $aktivni = Tournament::where('datum_zavrsetka', '>=', $danas)
            ->withCount('teams') // Ovo dodaje teams_count u svaki turnir
            ->get();

        $zavrseni = Tournament::where('datum_zavrsetka', '<', $danas)
            ->withCount('teams')
            ->get();

        return view('dashboard', compact('aktivni', 'zavrseni'));
    }

    public function show(Tournament $tournament)
    {
        // Učitavamo broj timova i listu timova sa njihovim brojem bodova
        $tournament->loadCount('teams')->load(['teams' => function($query) {
            $query->orderBy('broj_bodova', 'desc'); // Rang lista
        }]);

        return view('tournament.show', compact('tournament'));
    }

    public function create()
    {
        return view('tournament.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'naziv' => 'required',
            'lokacija' => 'required',
            'rok_za_prijave' => 'required|date',
            'datum_pocetka' => 'required|date',
            'datum_zavrsetka' => 'required|date|after_or_equal:datum_pocetka',
        ]);

        Tournament::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Turnir je uspešno kreiran!');
    }

    public function generate(Tournament $tournament)
    {
        // Uzimamo sve timove
        $teams = $tournament->teams->pluck('id')->toArray();

        // Ako je neparan broj, dodajemo null (za slobodan tim)
        if (count($teams) % 2 != 0) {
            $teams[] = null;
        }

        $count = count($teams);
        $rounds = $count - 1; // Broj kola
        $half = $count / 2;
        $schedule = [];

        for ($i = 0; $i < $rounds; $i++) {
            for ($j = 0; $j < $half; $j++) {
                $home = $teams[$j];
                $away = $teams[$count - 1 - $j];

                // Dodajemo meč samo ako nijedan tim nije null (slobodan)
                if ($home !== null && $away !== null) {
                    $schedule[$i][] = [
                        'home' => $home,
                        'away' => $away
                    ];
                }
            }

            // ISPRAVNA ROTACIJA:
            // Uzmi poslednji element i ubaci ga na poziciju 1 (indeks 1), fiksirajući element na indeksu 0
            $lastElement = array_pop($teams);
            array_splice($teams, 1, 0, [$lastElement]);
        }

        return view('tournament.generate', compact('tournament', 'schedule'));
    }
    public function storeSchedule(Request $request, Tournament $tournament)
    {
        // Brišemo stare utakmice ovog turnira pre nego što sačuvamo nove
        Utakmica::where('tournament_id', $tournament->id)->delete();

        $teams = $tournament->teams->pluck('id')->toArray();

        if (count($teams) % 2 != 0) {
            $teams[] = null;
        }

        $count = count($teams);
        $rounds = $count - 1;
        $half = $count / 2;

        for ($i = 0; $i < $rounds; $i++) {
            for ($j = 0; $j < $half; $j++) {
                $home = $teams[$j];
                $away = $teams[$count - 1 - $j];

                if ($home !== null && $away !== null) {
                    // KORISTIMO TVOJ MODEL I KOLONE
                    Utakmica::create([
                        'tournament_id' => $tournament->id,
                        'domaci_tim_id' => $home,
                        'strani_tim_id' => $away,
                        'mesto'         => $tournament->lokacija,
                        'status'        => 'zakazano', // Mora biti isto kao u migraciji (enum)
                        'user_id'       => Auth::id(),
                        'rezultat'      => '0:0',
                        'kolo'          => $i + 1, // OBAVEZNO DODAJ OVO
                    ]);
                }
            }
            // Rotacija
            $pivot = array_shift($teams);
            array_splice($teams, 1, 0, [array_pop($teams)]);
            array_unshift($teams, $pivot);
        }

        return redirect()->route('dashboard')->with('success', 'Raspored je trajno sačuvan u bazi!');
    }
}
