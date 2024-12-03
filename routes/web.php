<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('index');
});

Route::get('/DailyMenu', [MenuController::class, 'viewDailyMenu']);
Route::get('/Orders', [MenuController::class, 'viewOrders']);
Route::get('/Reservations', [MenuController::class, 'viewReservations']);
Route::get('/PhotoGallery', [MenuController::class, 'viewPhotoGallery']);
Route::get('/Contact', [MenuController::class, 'viewContact']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
