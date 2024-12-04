<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Supabase\createClient;

class UserController extends Controller
{
    protected $supabase;

    public function __construct()
    {
        // Inicializácia Supabase klienta s URL a kľúčom
        $this->supabase = createClient(
            env('SUPABASE_URL'),
            env('SUPABASE_API_KEY')
        );
    }

    // CREATE - Vloženie nového používateľa
    public function store(Request $request)
    {
        // Získanie údajov z požiadavky
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        // Uloženie nového používateľa do tabuľky 'user'
        $response = $this->supabase->from('user')->insert([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),  // Ukladanie hesla šifrované
        ]);

        if ($response->status() === 200) {
            return response()->json(['message' => 'Používateľ bol úspešne pridaný!'], 201);
        } else {
            return response()->json(['error' => 'Chyba pri vytváraní používateľa.'], 500);
        }
    }

    // READ - Získanie všetkých používateľov
    public function index()
    {
        $response = $this->supabase->from('user')->select('*')->execute();

        if ($response->status() === 200) {
            return response()->json($response->data);
        } else {
            return response()->json(['error' => 'Chyba pri načítaní používateľov.'], 500);
        }
    }

    // UPDATE - Aktualizovanie existujúceho používateľa
    public function update(Request $request, $id)
    {
        // Získanie údajov z požiadavky
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        // Aktualizovanie používateľa v tabuľke 'user'
        $response = $this->supabase->from('user')
            ->update([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),  // Ukladanie hesla šifrované
            ])
            ->eq('id', $id)
            ->execute();

        if ($response->status() === 200) {
            return response()->json(['message' => 'Používateľ bol úspešne aktualizovaný!']);
        } else {
            return response()->json(['error' => 'Chyba pri aktualizovaní používateľa.'], 500);
        }
    }

    // DELETE - Odstránenie používateľa
    public function destroy($id)
    {
        // Odstránenie používateľa z tabuľky 'user'
        $response = $this->supabase->from('user')->delete()->eq('id', $id)->execute();

        if ($response->status() === 200) {
            return response()->json(['message' => 'Používateľ bol úspešne odstránený!']);
        } else {
            return response()->json(['error' => 'Chyba pri odstraňovaní používateľa.'], 500);
        }
    }
}
