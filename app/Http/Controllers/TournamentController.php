<?php

namespace App\Http\Controllers;

use App\Http\Requests\TournamentStoreRequest;
use App\Http\Requests\TournamentUpdateRequest;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TournamentController extends Controller
{
    public function index(Request $request): Response
    {
        $tournaments = Tournament::all();

        return view('tournament.index', [
            'tournaments' => $tournaments,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('tournament.create');
    }

    public function store(TournamentStoreRequest $request): Response
    {
        $tournament = Tournament::create($request->validated());

        $request->session()->flash('tournament.id', $tournament->id);

        return redirect()->route('tournaments.index');
    }

    public function show(Request $request, Tournament $tournament): Response
    {
        return view('tournament.show', [
            'tournament' => $tournament,
        ]);
    }

    public function edit(Request $request, Tournament $tournament): Response
    {
        return view('tournament.edit', [
            'tournament' => $tournament,
        ]);
    }

    public function update(TournamentUpdateRequest $request, Tournament $tournament): Response
    {
        $tournament->update($request->validated());

        $request->session()->flash('tournament.id', $tournament->id);

        return redirect()->route('tournaments.index');
    }

    public function destroy(Request $request, Tournament $tournament): Response
    {
        $tournament->delete();

        return redirect()->route('tournaments.index');
    }
}
