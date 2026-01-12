<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::all();

        return view('team.index', [
            'teams' => $teams,
        ]);
    }

    public function create(Tournament $tournament)
    {
        return view('tournament.register_team', compact('tournament'));
    }

    public function show(Request $request, Team $team)
    {
        return view('team.show', [
            'team' => $team,
        ]);
    }

    public function edit(Request $request, Team $team)
    {
        return view('team.edit', [
            'team' => $team,
        ]);
    }

    public function update(TeamUpdateRequest $request, Team $team)
    {
        $team->update($request->validated());

        $request->session()->flash('team.id', $team->id);

        return redirect()->route('teams.index');
    }

    public function destroy(Request $request, Team $team)
    {
        $team->delete();

        return redirect()->route('teams.index');
    }

    public function store(Request $request, Tournament $tournament)
    {
        // Validacija za 12 igrača (dodaj ovo da osiguraš bazu)
        if (!$request->has('players') || count($request->players) !== 12) {
            return back()->withErrors(['players' => 'Morate dodati tačno 12 igrača.']);
        }

        // Tvoj postojeći kod za čuvanje...
        $team = \App\Models\Team::create([
            'user_id' => Auth::id(),
            'tournament_id' => $tournament->id,
            'naziv' => $request->naziv,
            'grad' => $request->grad,
            'broj_bodova' => 0
        ]);

        foreach ($request->players as $playerData) {
            $team->players()->create($playerData);
        }

        // Ovde proveri da li ti se ruta zove tournaments.show ili tournament.show
        return redirect()->route('tournaments.show', $tournament->id)
                        ->with('success', 'Uspešno ste prijavili tim!');
    }
}
