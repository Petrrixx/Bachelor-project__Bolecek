<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function showForm()
    {
        return view('reservation.reservations');
    }

    public function store(Request $request)
    {
        // Validácia požiadavky
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'guests' => 'required|integer|min:1',
            'terms' => 'accepted',
        ]);

        // Vytvorenie rezervácie
        $reservation = Reservation::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'guests' => $validated['guests'],
            'accept' => false,
        ]);

        return redirect()->back()->with('success', 'Rezervácia bola úspešne vytvorená.');
    }

    public function adminIndex()
    {
        $reservations = Reservation::orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        return view('reservation.reservationsAdmin', compact('reservations'));
    }

    public function approve($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->accept = true;
        $reservation->save();

        return redirect()->route('reservation.admin')->with('success', 'Rezervácia bola schválená.');
    }

    public function reject($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('reservation.admin')->with('success', 'Rezervácia bola odmietnutá a odstránená.');
    }
}