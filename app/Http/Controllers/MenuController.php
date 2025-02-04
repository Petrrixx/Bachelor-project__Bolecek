<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JedalnyListok;

class MenuController extends Controller
{
    public function viewDailyMenu()
    {
        // Načítame položky a zoradíme ich napr. podľa kategórie a id
        $items = JedalnyListok::orderBy('category')->orderBy('id')->get();
        // Zoskupíme položky podľa kategórie
        $groupedItems = $items->groupBy('category');
        return view('menu.menu', compact('groupedItems'));
    }

    public function viewPhotoGallery()
    {
        $directory = public_path('images/fotogaleria');
        // Načítame zoznam súborov a odstránime '.' a '..'
        $files = array_values(array_diff(scandir($directory), ['.', '..']));
        return view('photogallery', compact('files'));
    }


    public function viewOrders()
    {
        return view('orders.orders'); // orders.blade.php
    }

    public function viewReservations()
    {
        return view('reservation/reservations');
    }

    public function viewContact()
    {
        return view('contact.contact');
    }

    public function viewAuth()
    {
        return view("auth/auth");
    }
}
