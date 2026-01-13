<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UtakmicaController;
use Illuminate\Support\Facades\Route;


// 1. Početna strana vodi na Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Custom Autentifikacija
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

// 3. Zaštićene rute (Samo za ulogovane korisnike)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [TournamentController::class, 'index'])->name('dashboard');

    // Rute za Turnir (koristimo 'tournaments.show' jer ga Blade fajlovi već traže)
    Route::get('/tournament/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');


    Route::get('/tournament/{tournament}/register-team', [TeamController::class, 'create'])->name('teams.register');
    Route::post('/tournament/{tournament}/register-team', [TeamController::class, 'store'])->name('teams.store');

    Route::resource('teams', TeamController::class)->except(['create', 'store']);
    Route::resource('players', PlayerController::class);
    Route::resource('utakmicas', UtakmicaController::class);

    Route::get('/tournament/{tournament}/generate', [TournamentController::class, 'generate'])->name('tournaments.generate');
    Route::post('/tournament/{tournament}/store-schedule', [TournamentController::class, 'storeSchedule'])->name('tournaments.storeSchedule');
});
