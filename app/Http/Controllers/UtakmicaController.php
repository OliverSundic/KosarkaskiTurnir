<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtakmicaStoreRequest;
use App\Http\Requests\UtakmicaUpdateRequest;
use App\Models\Utakmica;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UtakmicaController extends Controller
{
    public function index(Request $request)
    {
        $utakmicas = Utakmica::all();

        return view('utakmica.index', [
            'utakmicas' => $utakmicas,
        ]);
    }

    public function create(Request $request)
    {
        return view('utakmica.create');
    }

    public function store(UtakmicaStoreRequest $request)
    {
        $utakmica = Utakmica::create($request->validated());

        $request->session()->flash('utakmica.id', $utakmica->id);

        return redirect()->route('utakmicas.index');
    }

    public function show(Request $request, Utakmica $utakmica)
    {
        return view('utakmica.show', [
            'utakmica' => $utakmica,
        ]);
    }

    public function edit(Request $request, Utakmica $utakmica)
    {
        return view('utakmica.edit', [
            'utakmica' => $utakmica,
        ]);
    }

    public function update(UtakmicaUpdateRequest $request, Utakmica $utakmica)
    {
        $utakmica->update($request->validated());

        $request->session()->flash('utakmica.id', $utakmica->id);

        return redirect()->route('utakmicas.index');
    }

    public function destroy(Request $request, Utakmica $utakmica)
    {
        $utakmica->delete();

        return redirect()->route('utakmicas.index');
    }

    public function updateScore(Request $request, Utakmica $utakmica)
    {
        // 1. Validacija unosa
        $request->validate([
            'home_points' => 'required|integer|min:0',
            'away_points' => 'required|integer|min:0',
        ]);

        $p1 = $request->home_points;
        $p2 = $request->away_points;

        // Čuvamo rezultat u formatu "85:80"
        $utakmica->rezultat = $p1 . ':' . $p2;

        // 2. Logika za dugme "Završi utakmicu"
        if ($request->action == 'finish' && $utakmica->status != 'zavrseno') {
            $utakmica->status = 'zavrseno';

            // Automatsko bodovanje u tabeli teams (Košarkaška pravila: Pobeda 2, Poraz 1)
            $domaci = $utakmica->domaciTim;
            $strani = $utakmica->straniTim;

            if ($p1 > $p2) {
                $domaci->increment('broj_bodova', 2);
                $strani->increment('broj_bodova', 1);
            } elseif ($p2 > $p1) {
                $strani->increment('broj_bodova', 2);
                $domaci->increment('broj_bodova', 1);
            }
        }
        // 3. Logika za otkazivanje
        elseif ($request->action == 'cancel') {
            $utakmica->status = 'otkazano';
        }

        $utakmica->save();

        return redirect()->route('tournaments.show', $utakmica->tournament_id)
                        ->with('success', 'Utakmica je uspešno ažurirana.');
    }

}
