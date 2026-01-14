<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerStoreRequest;
use App\Http\Requests\PlayerUpdateRequest;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request): Response
    {
        $players = Player::all();

        return view('player.index', [
            'players' => $players,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('player.create');
    }

    public function store(PlayerStoreRequest $request): Response
    {
        $player = Player::create($request->validated());

        $request->session()->flash('player.id', $player->id);

        return redirect()->route('players.index');
    }

    public function show(Request $request, Player $player): Response
    {
        return view('player.show', [
            'player' => $player,
        ]);
    }

    public function edit(Request $request, Player $player): Response
    {
        return view('player.edit', [
            'player' => $player,
        ]);
    }

    public function update(PlayerUpdateRequest $request, Player $player): Response
    {
        $player->update($request->validated());

        $request->session()->flash('player.id', $player->id);

        return redirect()->route('players.index');
    }

    public function destroy(Request $request, Player $player): Response
    {
        $player->delete();

        return redirect()->route('players.index');
    }
}
