<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UtakmicaController;
use Illuminate\Support\Facades\Route;

// 1. Početna strana
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Autentifikacija
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

// 3. Zaštićene rute
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [TournamentController::class, 'index'])->name('dashboard');

    // --- TURNIRI (VAŽAN REDOSLED!) ---

    // Fiksne rute idu PRVE
    Route::get('/tournament/create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournament/store', [TournamentController::class, 'store'])->name('tournaments.store');

    // Dinamičke rute (sa {tournament}) idu DRUGE
    Route::get('/tournament/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
    Route::get('/tournament/{tournament}/generate', [TournamentController::class, 'generate'])->name('tournaments.generate');
    Route::post('/tournament/{tournament}/store-schedule', [TournamentController::class, 'storeSchedule'])->name('tournaments.storeSchedule');

    // --- TIMOVI I IGRAČI ---
    Route::get('/tournament/{tournament}/register-team', [TeamController::class, 'create'])->name('teams.register');
    Route::post('/tournament/{tournament}/register-team', [TeamController::class, 'store'])->name('teams.store');

    Route::resource('teams', TeamController::class)->except(['create', 'store']);
    Route::resource('players', PlayerController::class);

    // --- UTAKMICE ---
    // Specijalna ruta za update rezultata mora ići IZNAD resource-a
    Route::patch('/utakmicas/{utakmica}/update-score', [UtakmicaController::class, 'updateScore'])->name('utakmicas.update_score');
    Route::resource('utakmicas', UtakmicaController::class);
    Route::get('/tournaments/{tournament}/leaderboard', [TournamentController::class, 'leaderboard'])
    ->name('tournaments.leaderboard');
});
