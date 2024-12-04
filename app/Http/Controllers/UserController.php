<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\User;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Hash;
//
//class UserController extends Controller
//{
//    /**
//     * Konštruktor - aplikovať middleware
//     */
//    public function __construct()
//    {
//        // Middleware zabezpečujúci, že prístup k CRUD operáciám majú len autentifikovaní administrátori
//        $this->middleware(['auth', 'admin']);
//    }
//
//    /**
//     * Zobrazenie zoznamu všetkých užívateľov.
//     *
//     * @return \Illuminate\View\View
//     */
//    public function index()
//    {
//        $users = User::all();
//        return view('admin.users.index', compact('users'));
//    }
//
//    /**
//     * Zobrazenie formulára pre vytvorenie nového užívateľa.
//     *
//     * @return \Illuminate\View\View
//     */
//    public function create()
//    {
//        return view('admin.users.create');
//    }
//
//    /**
//     * Uloženie nového užívateľa do databázy.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\RedirectResponse
//     */
//    public function store(Request $request)
//    {
//        // Validácia vstupných údajov
//        $validatedData = $request->validate([
//            'name' => 'required|string|max:255', // Namiesto 'full_name'
//            'email' => 'required|email|unique:user,email', // Tabuľka 'user'
//            'password' => 'required|string|min:6|confirmed',
//            'contact' => 'nullable|string|unique:user,contact',
//            'isAdmin' => 'required|boolean',
//        ]);
//
//        // Vytvorenie nového užívateľa
//        User::create([
//            'name' => $validatedData['name'], // Namiesto 'full_name'
//            'email' => $validatedData['email'],
//            'password' => Hash::make($validatedData['password']),
//            'contact' => $validatedData['contact'] ?? null,
//            'verified' => false,
//            'isAdmin' => $validatedData['isAdmin'],
//        ]);
//
//        return redirect()->route('admin.users.index')->with('success', 'Užívateľ bol úspešne vytvorený.');
//    }
//
//    /**
//     * Zobrazenie detailu konkrétneho užívateľa.
//     *
//     * @param  \App\Models\User  $user
//     * @return \Illuminate\View\View
//     */
//    public function show(User $user)
//    {
//        return view('admin.users.show', compact('user'));
//    }
//
//    /**
//     * Zobrazenie formulára pre úpravu užívateľa.
//     *
//     * @param  \App\Models\User  $user
//     * @return \Illuminate\View\View
//     */
//    public function edit(User $user)
//    {
//        return view('admin.users.edit', compact('user'));
//    }
//
//    /**
//     * Aktualizácia existujúceho užívateľa v databáze.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  \App\Models\User  $user
//     * @return \Illuminate\Http\RedirectResponse
//     */
//    public function update(Request $request, User $user)
//    {
//        // Validácia vstupných údajov
//        $validatedData = $request->validate([
//            'name' => 'required|string|max:255', // Namiesto 'full_name'
//            'email' => 'required|email|unique:user,email,' . $user->id, // Tabuľka 'user'
//            'password' => 'nullable|string|min:6|confirmed',
//            'contact' => 'nullable|string|unique:user,contact,' . $user->id,
//            'isAdmin' => 'required|boolean',
//        ]);
//
//        // Aktualizácia údajov užívateľa
//        $user->name = $validatedData['name']; // Namiesto 'full_name'
//        $user->email = $validatedData['email'];
//        if (!empty($validatedData['password'])) {
//            $user->password = Hash::make($validatedData['password']);
//        }
//        $user->contact = $validatedData['contact'] ?? $user->contact;
//        $user->isAdmin = $validatedData['isAdmin'];
//        $user->save();
//
//        return redirect()->route('admin.users.index')->with('success', 'Užívateľ bol úspešne aktualizovaný.');
//    }
//
//    /**
//     * Odstránenie užívateľa z databázy.
//     *
//     * @param  \App\Models\User  $user
//     * @return \Illuminate\Http\RedirectResponse
//     */
//    public function destroy(User $user)
//    {
//        $user->delete();
//
//        return redirect()->route('admin.users.index')->with('success', 'Užívateľ bol úspešne odstránený.');
//    }
//}
