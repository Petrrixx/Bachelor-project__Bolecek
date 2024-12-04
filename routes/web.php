<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Hlavná stránka
Route::get('/', function () {
    return view('index');
});

// Routy pre Menu, Orders, Reservations, atď.
Route::get('/DailyMenu', [MenuController::class, 'viewDailyMenu']);
Route::get('/Orders', [MenuController::class, 'viewOrders']);
Route::get('/Reservations', [MenuController::class, 'viewReservations']);
Route::get('/PhotoGallery', [MenuController::class, 'viewPhotoGallery']);
Route::get('/Contact', [MenuController::class, 'viewContact']);

// Registrácia a prihlásenie
Route::get('/login', function() {
    return view('login');
})->name('login');

Route::post('/login.submit', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', function() {
    return view('register');
})->name('register');

Route::post('/register.submit', [AuthController::class, 'register'])->name('register.submit');

// Po prihlásení presmerovanie na dashboard
Route::get('/dashboard', function() {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

// Routy pre profil, aktualizáciu a vymazanie používateľa - prístupné len prihláseným
Route::middleware('auth')->group(function() {
    Route::get('/profile', function() {
        return view('user.profile');
    })->name('profile');

    Route::post('/update-user', [AuthController::class, 'updateUser'])->name('update.user');
    Route::post('/delete-user', [AuthController::class, 'deleteUser'])->name('delete.user');
});

// CRUD operácie pre používateľov (Admin alebo iný typ autorizácie)
Route::get('/users', [UserController::class, 'index']); // Zobrazenie všetkých používateľov
Route::post('/users', [UserController::class, 'store']); // Vytvorenie nového používateľa
Route::put('/users/{id}', [UserController::class, 'update']); // Aktualizovanie používateľa podľa ID
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Odstránenie používateľa podľa ID

// Route pre logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


