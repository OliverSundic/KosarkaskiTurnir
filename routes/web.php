<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UtakmicaController;
use Illuminate\Support\Facades\Route;

// 1. Pocetna strana vodi na Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Tvoja Custom Autentifikacija (Dizajn iz Figme)
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

// 3. Zaštićene rute (Samo za ulogovane korisnike)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Resursi za aplikaciju
    Route::resource('tournaments', TournamentController::class);
    Route::resource('teams', TeamController::class);
    Route::resource('players', PlayerController::class);
    Route::resource('utakmicas', UtakmicaController::class);

    // Profil (Opciono, ostavljeno iz Breeze-a)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
