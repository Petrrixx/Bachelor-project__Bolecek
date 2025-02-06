<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
//use App\Http\Controllers\UserController;

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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\JedalnyListokController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PhotoGalleryController;


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
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UsersController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

// Zobrazenie profilu
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

// Úprava profilu
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Vymazanie účtu
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Hlavná stránka
Route::get('/', function () {
    return view('index');
})->name('index');

// Rezervácie
Route::get('/Reservations', [MenuController::class, 'viewReservations']);

Route::get('/reservation', [ReservationController::class, 'showForm'])->name('reservation.form');
Route::post('/reservation/submit', [ReservationController::class, 'store'])->name('reservation.submit');

// Administratívny prehľad rezervácií (len s autentifikáciou)
Route::get('reservation/admin', [ReservationController::class, 'adminIndex'])->name('reservation.admin');
Route::post('reservation/{id}/approve', [ReservationController::class, 'approve'])->name('reservation.approve');
Route::post('reservation/{id}/reject', [ReservationController::class, 'reject'])->name('reservation.reject');

Route::patch('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
Route::get('/reservations/user', [ReservationController::class, 'viewUser'])->name('reservation.user');


// Denné menu pre verejnosť – spracované v MenuController
Route::get('/DailyMenu', [MenuController::class, 'viewDailyMenu'])->name('dailyMenu');

// Administratívny pohľad na denné menu a CRUD operácie – spracované v JedalnyListokController
Route::get('/menu/admin', [JedalnyListokController::class, 'indexAdmin'])->name('menu.admin');
Route::get('/menu/create', [JedalnyListokController::class, 'create'])->name('menu.create');
Route::post('/menu', [JedalnyListokController::class, 'store'])->name('menu.store');
Route::get('/menu/{id}/edit', [JedalnyListokController::class, 'edit'])->name('menu.edit');
Route::patch('/menu/{id}', [JedalnyListokController::class, 'update'])->name('menu.update');
Route::delete('/menu/{id}', [JedalnyListokController::class, 'destroy'])->name('menu.destroy');

// Objednávky
Route::get('/Orders', [MenuController::class, 'viewOrders']);

// Objednávky používateľa
Route::get('/orders/user', [OrderController::class, 'indexUser'])->name('orders.user.index');

// Objednávky admina
Route::get('/orders/admin', [OrderController::class, 'indexAdmin'])->name('orders.admin.index');

// Vytvorenie objednávky
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// Úprava objednávky (používateľ)
Route::patch('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');

// Zrušenie objednávky (používateľ – len status)
Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

// Aktualizácia statusu (admin)
Route::patch('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

// Vymazanie objednávky (admin)
Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

// Hromadné vymazanie objednávok (admin)
Route::delete('/orders/delete-multiple', [OrderController::class, 'deleteMultiple'])->name('orders.deleteMultiple');

// Úprava objednávky používateľa
Route::patch('/orders/user/{id}', [OrderController::class, 'userUpdate'])->name('orders.user.update');

// Fotogaléria
Route::get('/PhotoGallery', [MenuController::class, 'viewPhotoGallery']);
Route::post('/photogallery/upload', [PhotoGalleryController::class, 'upload'])->name('photogallery.upload');

// Kontakty
Route::get('/Contact', [MenuController::class, 'viewContact'])->name('contact');
Route::get('/contact/admin', [ContactController::class, 'adminMailbox'])->name('contact.admin');
// Kontaktný formulár
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/contact/message/{id}', [ContactController::class, 'getMessage'])->name('contact.message');

// Admin rozhranie pre správy
Route::get('/contact/messages', [ContactController::class, 'indexAdmin'])->name('contact.messagesAdmin');
Route::get('/contact/messages/{id}', [ContactController::class, 'showAdmin'])->name('contact.messageDetail');
Route::delete('/contact/messages/{id}', [ContactController::class, 'destroy'])->name('contact.messageDelete');
Route::delete('/contact/messages/delete-multiple', [ContactController::class, 'deleteMultiple'])->name('contact.messagesDeleteMultiple');
Route::delete('/contact/delete-all', [ContactController::class, 'deleteAll'])->name('contact.deleteAll');
Route::get('/contact/get-message/{id}', [ContactController::class, 'getMessage'])->name('contact.getMessage');

