<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JedalnyListok;
use Illuminate\Support\Facades\Auth;

class JedalnyListokController extends Controller
{
    /**
     * Zobrazenie denného menu pre všetkých užívateľov.
     * GET /menu
     */
//    public function index()
//    {
//        $items = JedalnyListok::all();
//        return view('menu.menu', compact('items'));
//    }

    /**
     * Administratívny pohľad na menu – prístup len pre admina.
     * GET /menu/admin
     */
    public function indexAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('menu')->with('error', 'Nemáte prístup.');
        }
        $items = JedalnyListok::all();
        return view('menu.menuAdmin', compact('items'));
    }

    /**
     * Zobrazenie formulára na pridanie nového jedla (admin).
     * GET /menu/create
     */
    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('menu')->with('error', 'Nemáte prístup.');
        }
        return view('menu.create');
    }

    /**
     * Uloženie nového jedla (admin).
     * POST /menu
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('menu')->with('error', 'Nemáte prístup.');
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

    /**
     * Zobrazenie formulára na editáciu jedla (admin).
     * GET /menu/{id}/edit
     */
    public function edit($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('menu')->with('error', 'Nemáte prístup.');
        }
        $item = JedalnyListok::findOrFail($id);
        return view('menu.edit', compact('item'));
    }

    /**
     * Aktualizácia jedla (admin).
     * PATCH /menu/{id}
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('menu')->with('error', 'Nemáte prístup.');
        }
        $validated = $request->validate([
            'food_name' => 'required|string',
            'priloha_name' => 'nullable|string',
            'obloha_name' => 'nullable|string',
            'all_allergens' => 'nullable|string',
        ]);
        $item = JedalnyListok::findOrFail($id);
        $item->update($validated);
        return redirect()->route('menu.admin')->with('success', 'Jedlo bolo upravené.');
    }

    /**
     * Vymazanie jedla (admin).
     * DELETE /menu/{id}
     */
    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('menu')->with('error', 'Nemáte prístup.');
        }
        $item = JedalnyListok::findOrFail($id);
        $item->delete();
        return redirect()->route('menu.admin')->with('success', 'Jedlo bolo vymazané.');
    }
}
