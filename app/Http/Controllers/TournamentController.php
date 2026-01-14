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
        // OVO DODAJ: Učitava broj povezanih timova
        $tournament->loadCount('teams');

        // Ostatak koda ostaje identičan (tvoja logika za utakmice i pobednika)
        $utakmice = $tournament->utakmicas()->get()->groupBy('kolo');

        $ukupno = $tournament->utakmicas()->count();
        $zavrseno = $tournament->utakmicas()->where('status', 'zavrseno')->count();
        $turnirJeGotov = ($ukupno > 0) && ($ukupno === $zavrseno);

        $pobednik = null;
        if ($turnirJeGotov) {
            $pobednik = $tournament->teams->map(function($tim) use ($tournament) {
                $bodovi = 0;
                $mecevi = $tournament->utakmicas()->where('status', 'zavrseno')
                    ->where(function($q) use ($tim) {
                        $q->where('domaci_tim_id', $tim->id)->orWhere('strani_tim_id', $tim->id);
                    })->get();

                foreach ($mecevi as $m) {
                    $rez = explode(':', $m->rezultat);
                    if (count($rez) == 2) {
                        if ($m->domaci_tim_id == $tim->id) $bodovi += ($rez[0] > $rez[1]) ? 2 : 1;
                        else $bodovi += ($rez[1] > $rez[0]) ? 2 : 1;
                    }
                }
                return (object) ['naziv' => $tim->naziv, 'bodovi' => $bodovi];
            })->sortByDesc('bodovi')->first();
        }

        return view('tournament.show', compact('tournament', 'utakmice', 'pobednik'));
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
        $timovi = $tournament->teams;

        $rangLista = $timovi->map(function($tim) use ($tournament) {
            $bodovi = 0;
            $utakmice = \App\Models\Utakmica::where('tournament_id', $tournament->id)
                ->where('status', 'zavrseno')
                ->where(function($q) use ($tim) {
                    $q->where('domaci_tim_id', $tim->id)
                    ->orWhere('strani_tim_id', $tim->id);
                })->get();

            foreach ($utakmice as $utakmica) {
                $rezultat = explode(':', $utakmica->rezultat);
                if (count($rezultat) !== 2) continue;
                $p1 = (int)$rezultat[0];
                $p2 = (int)$rezultat[1];

                if ($utakmica->domaci_tim_id == $tim->id) {
                    $bodovi += ($p1 > $p2) ? 2 : 1;
                } else {
                    $bodovi += ($p2 > $p1) ? 2 : 1;
                }
            }

            return (object) [
                'naziv' => $tim->naziv,
                'bodovi' => $bodovi
            ];
        });

        $rangLista = $rangLista->sortByDesc('bodovi')->values();


        $ukupno = $tournament->utakmicas()->count();
        $zavrseno = $tournament->utakmicas()->where('status', 'zavrseno')->count();
        $turnirJeGotov = ($ukupno > 0) && ($ukupno === $zavrseno);

        $pobednik = null;
        if ($turnirJeGotov && $rangLista->isNotEmpty()) {
            $pobednik = $rangLista->first();
        }

        return view('tournament.leaderboard', [
            'tournament' => $tournament,
            'timovi' => $rangLista,
            'pobednik' => $pobednik
        ]);
    }
}
