<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JedalnyListok;
use Illuminate\Support\Facades\Auth;

class JedalnyListokController extends Controller
{
    public function indexAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('dailyMenu')->with('error', 'Nemáte prístup.');
        }
        $items = JedalnyListok::all();
        return view('menu.menuAdmin', compact('items'));
    }

    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('dailyMenu')->with('error', 'Nemáte prístup.');
        }
        return view('menu.menuCreate');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('dailyMenu')->with('error', 'Nemáte prístup.');
        }

        $validated = $request->validate([
            'food_name' => 'required|string',
            'priloha_name' => 'nullable|string',
            'obloha_name' => 'nullable|string',
            'all_allergens' => 'nullable|string',
        ]);

        JedalnyListok::create($validated);
        return redirect()->route('menu.admin')->with('success', 'Jedlo bolo pridané.');
    }

    public function edit($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('dailyMenu')->with('error', 'Nemáte prístup.');
        }
        $item = JedalnyListok::findOrFail($id);
        return view('menu.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        // Overenie, či je používateľ prihlásený a či je administrátor
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return response()->json(['message' => 'Nemáte prístup.'], 403);
        }

        // Validujeme len polia, ktoré je povolené meniť: food_name a all_allergens.
        // Polia priloha_name a obloha_name ignorujeme, aby sa nemenili.
        $validated = $request->validate([
            'food_name'    => 'required|string',
            'all_allergens'=> 'nullable|string',
        ]);

        // Načítame záznam podľa ID
        $item = JedalnyListok::findOrFail($id);

        // Aktualizujeme len povolené polia
        $item->food_name    = $validated['food_name'];
        $item->all_allergens = $validated['all_allergens'] ?? $item->all_allergens;
        $item->save();

        // Vrátime JSON odpoveď pre AJAX volanie.
        // Polia priloha_name a obloha_name ostávajú nezmenené.
        return response()->json([
            'id'            => $item->id,
            'food_name'     => $item->food_name,
            'priloha_name'  => $item->priloha_name, // zostáva pôvodná hodnota
            'obloha_name'   => $item->obloha_name,  // zostáva pôvodná hodnota
            'all_allergens' => $item->all_allergens,
        ]);
    }


    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('dailyMenu')->with('error', 'Nemáte prístup.');
        }
        $item = JedalnyListok::findOrFail($id);
        $item->delete();
        return redirect()->route('menu.admin')->with('success', 'Jedlo bolo vymazané.');
    }
}
