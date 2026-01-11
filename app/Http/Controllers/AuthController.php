<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        return back()->withErrors(['email' => 'Pogrešni podaci.']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:fan,organizer,referee',
        ], [
        // Ovde definišeš poruke na srpskom
        'email.unique' => 'Ova email adresa je već zauzeta.',
        'email.email' => 'Molimo unesite ispravnu email adresu.',
        'password.min' => 'Lozinka mora imati najmanje :min karaktera.',
        'required' => 'Polje :attribute je obavezno.',
        ]);

        \App\Models\User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('login')->with('success', 'Uspešno ste se registrovali! Sada se možete ulogovati.');
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }
}
