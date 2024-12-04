<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function viewDailyMenu()
    {
        return view('menu'); // odkazuje na resources/views/menu.blade.php
    }

    public function viewOrders()
    {
        return view('orders'); // orders.blade.php
    }

    public function viewReservations()
    {
        return view('reservation/reservations');
    }

    public function viewPhotoGallery()
    {
        return view('photogallery');
    }

    public function viewContact()
    {
        return view('contact');
    }

    public function viewAuth()
    {
        return view("auth/auth");
    }
}
