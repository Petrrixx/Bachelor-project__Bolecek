<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $supabaseService;

    public function __construct(SupabaseService $supabaseService)
    {
        $this->supabaseService = $supabaseService;
    }

    // Registrácia používateľa
    public function register(Request $request)
    {
        // Validácia vstupov
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // Heslo a potvrdenie hesla
            'contact' => 'nullable|regex:/^\+?[0-9]{10,15}$/',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Služba na vytvorenie používateľa
        $response = $this->supabaseService->createUser(
            $request->email,
            $request->password,
            [
                'full_name' => $request->name,
                'contact' => $request->contact
            ]
        );

        // V prípade úspechu presmerujeme na prihlásenie
        if ($response == 'User has been created!') {
            return redirect()->route('login')->with('success', 'Účet bol vytvorený. Prihláste sa!');
        }

        return back()->with('error', 'Chyba pri registrácii: ' . $response);
    }

    // Prihlásenie používateľa
    public function login(Request $request)
    {
        // Validácia vstupov
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Služba na prihlásenie používateľa
        $response = $this->supabaseService->signInUser($request->email, $request->password);

        // Ak prihlásenie prebehne úspešne, ulož token do session a presmeruj
        if (isset($response['access_token'])) {
            session(['access_token' => $response['access_token']]);
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Nesprávne prihlasovacie údaje');
    }

    // Odhlásenie používateľa
    public function logout(Request $request)
    {
        // Zrušiť session (token)
        session()->forget('access_token');
        session()->flush(); // (ak chceš úplne zrušiť všetky session dáta)

        // Presmerovanie na hlavnú stránku alebo prihlásenie
        return redirect('/')->with('success', 'Boli ste úspešne odhlásení');
    }


    // Získanie informácií o používateľovi (volanie na backend)
    public function getUserData(Request $request)
    {
        $access_token = session('access_token');
        $userData = $this->supabaseService->getUserData($access_token);

        return view('user.profile', compact('userData'));
    }
}
