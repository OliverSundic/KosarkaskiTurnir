<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtakmicaStoreRequest;
use App\Http\Requests\UtakmicaUpdateRequest;
use App\Models\Utakmica;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UtakmicaController extends Controller
{
    public function index(Request $request): Response
    {
        $utakmicas = Utakmica::all();

        return view('utakmica.index', [
            'utakmicas' => $utakmicas,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('utakmica.create');
    }

    public function store(UtakmicaStoreRequest $request): Response
    {
        $utakmica = Utakmica::create($request->validated());

        $request->session()->flash('utakmica.id', $utakmica->id);

        return redirect()->route('utakmicas.index');
    }

    public function show(Request $request, Utakmica $utakmica): Response
    {
        return view('utakmica.show', [
            'utakmica' => $utakmica,
        ]);
    }

    public function edit(Request $request, Utakmica $utakmica): Response
    {
        return view('utakmica.edit', [
            'utakmica' => $utakmica,
        ]);
    }

    public function update(UtakmicaUpdateRequest $request, Utakmica $utakmica): Response
    {
        $utakmica->update($request->validated());

        $request->session()->flash('utakmica.id', $utakmica->id);

        return redirect()->route('utakmicas.index');
    }

    public function destroy(Request $request, Utakmica $utakmica): Response
    {
        $utakmica->delete();

        return redirect()->route('utakmicas.index');
    }
}
