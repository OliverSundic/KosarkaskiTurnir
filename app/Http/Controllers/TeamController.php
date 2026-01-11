<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(Request $request): Response
    {
        $teams = Team::all();

        return view('team.index', [
            'teams' => $teams,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('team.create');
    }

    public function store(TeamStoreRequest $request): Response
    {
        $team = Team::create($request->validated());

        $request->session()->flash('team.id', $team->id);

        return redirect()->route('teams.index');
    }

    public function show(Request $request, Team $team): Response
    {
        return view('team.show', [
            'team' => $team,
        ]);
    }

    public function edit(Request $request, Team $team): Response
    {
        return view('team.edit', [
            'team' => $team,
        ]);
    }

    public function update(TeamUpdateRequest $request, Team $team): Response
    {
        $team->update($request->validated());

        $request->session()->flash('team.id', $team->id);

        return redirect()->route('teams.index');
    }

    public function destroy(Request $request, Team $team): Response
    {
        $team->delete();

        return redirect()->route('teams.index');
    }
}
