<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    // U metodi show koju smo malopre popravljali
    public function show(Tournament $tournament)
    {
        // loadCount radi isto što i withCount, ali na već postojećem objektu
        $tournament->loadCount('teams');

        return view('tournament.show', compact('tournament'));
    }
}
