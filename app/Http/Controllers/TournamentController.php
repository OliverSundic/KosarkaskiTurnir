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
        // 1. Tvoja postojeća logika za timove i rang listu
        $tournament->loadCount('teams')->load(['teams' => function($query) {
            $query->orderBy('broj_bodova', 'desc');
        }]);

        // 2. Dodajemo učitavanje utakmica i grupisanje po kolima
        // Ovo je deo koji ti je falio i zbog kog je izbacivao grešku
        $utakmice = $tournament->utakmicas()
            ->with(['domaciTim', 'straniTim'])
            ->get()
            ->groupBy('kolo');

        // 3. Šaljemo i $tournament i $utakmice u Blade
        return view('tournament.show', [
            'tournament' => $tournament,
            'utakmice' => $utakmice
        ]);
    }

    public function create()
    {
        return view('tournament.create');
    }

    public function store(Request $request)
    {
        // 1. Izbaci 'broj_timova' iz validacije jer ga ne šalješ kroz formu
        $request->validate([
            'naziv' => 'required|string|max:255',
            'lokacija' => 'required|string|max:255',
            'datum_pocetka' => 'required|date',
            'datum_zavrsetka' => 'required|date',
        ]);

        // 2. Kreiranje objekta
        $tournament = new Tournament();
        $tournament->user_id = Auth::id();
        $tournament->naziv = $request->naziv;
        $tournament->lokacija = $request->lokacija;
        $tournament->datum_pocetka = $request->datum_pocetka;
        $tournament->datum_zavrsetka = $request->datum_zavrsetka;

        // Ručno postavljamo na 0 pre čuvanja
        $tournament->broj_timova = 0;

        // 3. Čuvanje i redirect
        if($tournament->save()) {
            return redirect()->route('dashboard')->with('success', 'Turnir "' . $tournament->naziv . '" je uspešno kreiran!');
        }

        return "Greška pri čuvanju u bazu podataka.";
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
    public function leaderboard(Tournament $tournament)
    {
        // 1. Uzimamo sve timove turnira
        $timovi = $tournament->teams;

        // 2. Za svaki tim računamo bodove na osnovu završenih utakmica
        $rangLista = $timovi->map(function($tim) use ($tournament) {
            $bodovi = 0;

            // Tražimo sve završene utakmice gde je ovaj tim igrao
            $utakmice = \App\Models\Utakmica::where('tournament_id', $tournament->id)
                ->where('status', 'zavrseno')
                ->where(function($q) use ($tim) {
                    $q->where('domaci_tim_id', $tim->id)
                    ->orWhere('strani_tim_id', $tim->id);
                })->get();

            foreach ($utakmice as $utakmica) {
                $rezultat = explode(':', $utakmica->rezultat);
                if (count($rezultat) !== 2) continue;

                $p1 = (int)$rezultat[0]; // Poeni domaćih
                $p2 = (int)$rezultat[1]; // Poeni stranih

                if ($utakmica->domaci_tim_id == $tim->id) {
                    // Tim je bio domaćin
                    $bodovi += ($p1 > $p2) ? 2 : 1;
                } else {
                    // Tim je bio gost
                    $bodovi += ($p2 > $p1) ? 2 : 1;
                }
            }

            // Vraćamo privremeni objekat sa nazivom i sračunatim bodovima
            return (object) [
                'naziv' => $tim->naziv,
                'bodovi' => $bodovi
            ];
        });

        // 3. Sortiramo listu po bodovima (od najvećeg ka najmanjem)
        $rangLista = $rangLista->sortByDesc('bodovi');

        return view('tournament.leaderboard', [
            'tournament' => $tournament,
            'timovi' => $rangLista
        ]);
    }
}
