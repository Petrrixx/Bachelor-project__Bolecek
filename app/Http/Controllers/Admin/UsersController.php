<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Pre hashovanie hesla

class UsersController extends Controller
{
    public function index()
    {
        // Kontrola, či je aktuálny používateľ administrátor
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte oprávnenie na prístup k tejto stránke.');
        }

        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte oprávnenie na prístup k tejto stránke.');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte oprávnenie na prístup k tejto stránke.');
        }

        // Validácia údajov
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
            'contact' => 'nullable|string|max:20',
            'verified' => 'sometimes|boolean',
            'isAdmin' => 'sometimes|boolean',
        ]);

        // Vytvorenie používateľa
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'contact' => $validated['contact'] ?? null,
            'verified' => $request->has('verified') ? true : false,
            'isAdmin' => $request->has('isAdmin') ? true : false,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Používateľ bol úspešne vytvorený.');
    }

    public function edit($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte oprávnenie na prístup k tejto stránke.');
        }

        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }


    public function update(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte oprávnenie na prístup k tejto stránke.');
        }

        $user = User::findOrFail($id);

        // Validácia údajov
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'contact' => 'nullable|string|max:20',
            'verified' => 'sometimes|boolean',
            'isAdmin' => 'sometimes|boolean',
        ]);

        // Aktualizácia používateľa
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        if (isset($validated['contact'])) {
            $user->contact = $validated['contact'];
        }
        if (isset($validated['verified'])) {
            $user->verified = $validated['verified'];
        }
        if (isset($validated['isAdmin'])) {
            $user->isAdmin = $validated['isAdmin'];
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Používateľ bol úspešne upravený.');
    }

    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte oprávnenie na prístup k tejto stránke.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Používateľ bol úspešne vymazaný.');
    }
}
