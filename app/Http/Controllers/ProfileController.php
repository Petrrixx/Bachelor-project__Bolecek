<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('index')->with('error', 'Nemáte prístup.');
        }

        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        if (!Auth::check()) {
            return redirect()->route('index')->with('error', 'Nemáte prístup.');
        }

        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('index')->with('error', 'Nemáte prístup.');
        }

        $user = Auth::user();

        // Validácia údajov
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Aktualizácia mena, kontaktu a hesla
        $user->name = $validated['name'];
        $user->contact = $validated['contact'] ?? null;
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profil bol úspešne upravený.');
    }

    public function destroy()
    {
        if (!Auth::check()) {
            return redirect()->route('index')->with('error', 'Nemáte prístup.');
        }

        $user = Auth::user();

        // Vymazanie používateľa
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Váš účet bol úspešne vymazaný.');
    }
}
