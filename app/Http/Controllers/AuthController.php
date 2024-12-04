<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function showAuthForm(Request $request)
    {
        $type = $request->get('type', 'login');
        return view('auth.auth', ['type' => $type]);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Zadané údaje neboli nájdené.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        // pre testovanie
        // dd($request->all());

        // Validácia údajov
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
            'contact' => 'nullable|string|max:20',
            'terms' => 'required|accepted',
        ]);

        // Vytvorenie nového používateľa
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'contact' => $validated['contact'] ?? null,
            'verified' => false,
            'isAdmin' => false,
        ]);

        Auth::login($user);

        return redirect()->route('profile.show')->with('success', 'Úspešne ste sa zaregistrovali!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Úspešne ste sa odhlásili.');
    }
}
