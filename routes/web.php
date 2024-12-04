<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ReservationController;

//// Registrácia
//Route::post('/register.submit', [AuthController::class, 'register'])->name('register.submit');
//
//// Prihlásenie
//Route::post('/login.submit', [AuthController::class, 'login'])->name('login.submit');
//
//// Route pre logout
//Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//
//
//// Používateľské routy, ktoré vyžadujú autentifikáciu cez Supabase
//Route::middleware('auth.supabase')->group(function () {
//    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile'); // Profil
//    Route::post('/update-user', [AuthController::class, 'updateUser'])->name('update.user');
//    Route::post('/delete-user', [AuthController::class, 'deleteUser'])->name('delete.user');
//});
//
//// CRUD operácie pre používateľov (Admin alebo iný typ autorizácie)
//Route::get('/users', [UserController::class, 'index']); // Zobrazenie všetkých používateľov
//Route::post('/users', [UserController::class, 'store']); // Vytvorenie nového používateľa
//Route::put('/users/{id}', [UserController::class, 'update']); // Aktualizovanie používateľa podľa ID
//Route::delete('/users/{id}', [UserController::class, 'destroy']); // Odstránenie používateľa podľa ID
//
//Route::get('/test-database', [AuthController::class, 'testDatabaseConnection']);
//
//Route::post('/submit-reservation', [ReservationController::class, 'submitReservation'])->name('reservation.submit');
//Route::get('/reservation/success', [ReservationController::class, 'reservationSuccess'])->name('reservation/success');
//
//Route::get('/reservationsAdmin', [ReservationController::class, 'reservationsAdmin'])->name('reservation.admin');
//
//Route::resource('users', UserController::class);

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\ProfileController;

// Autentifikačné trasy
Route::get('/auth', [AuthController::class, 'showAuthForm'])->name('auth.auth');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profilová stránka
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show')->middleware('auth');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

//Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
//    Route::get('/users', [UsersController::class, 'index'])->name('admin.users.index');
//}); NEFUNGUJE MI MIDDLEWARE

// Admin Routes bez middleware isAdmin (kontrola v kontroléri)
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::resource('users', UsersController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

Route::middleware(['auth'])->group(function () {
    // Zobrazenie profilu
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Úprava profilu
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Vymazanie účtu
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Hlavná stránka
Route::get('/', function () {
    return view('index');
});

Route::get('/reservation', [ReservationController::class, 'showForm'])->name('reservation.form');
Route::post('/reservation/submit', [ReservationController::class, 'store'])->name('reservation.submit');
// Administratívny prehľad rezervácií (len s autentifikáciou)
Route::prefix('reservation')->middleware(['auth'])->group(function () {
    Route::get('/admin', [ReservationController::class, 'adminIndex'])->name('reservation.admin');
    Route::post('/{id}/approve', [ReservationController::class, 'approve'])->name('reservation.approve');
    Route::post('/{id}/reject', [ReservationController::class, 'reject'])->name('reservation.reject');
});

// Ostatné routy
Route::get('/DailyMenu', [MenuController::class, 'viewDailyMenu']);
Route::get('/Orders', [MenuController::class, 'viewOrders']);
Route::get('/Reservations', [MenuController::class, 'viewReservations']);
Route::get('/PhotoGallery', [MenuController::class, 'viewPhotoGallery']);
Route::get('/Contact', [MenuController::class, 'viewContact']);
