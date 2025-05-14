<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmationEmail;
use App\Mail\ReservationAdminMail;
use App\Rules\FitsIntoCapacity;
use App\Mail\ReservationApprovedMail;
use App\Mail\ReservationRejectedMail;

class ReservationController extends Controller
{
    public function showForm()
    {
        return view('reservation.reservations');
    }

    public function store(Request $request)
    {
        // Pridanie vlastnej validácie pre čas
        $openingRule = function ($attribute, $value, $fail) use ($request) {
            $date = $request->input('date');
            if (!$date) return;                   // čas validujeme až po výbere dátumu
    
            $day = \Carbon\Carbon::parse($date)->dayOfWeek; // 0 = Nedeľa, 5 = Piatok
            $min = '10:30';
            $max = in_array($day, [5,6,0]) ? '19:00' : '14:00';
    
            if ($value < $min || $value > $max) {
                $fail("Čas musí byť medzi $min a $max.");
            }
        };

        // Validácia požiadavky
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'user_contact' => 'required|string|max:15',
            'date' => 'required|date',
            'time' => ['required', 'date_format:H:i', $openingRule],
            'guests' => ['required','integer','min:1', new FitsIntoCapacity('date', 'time', 'guests')],
            'terms' => 'accepted',
        ]);

        // Vytvorenie rezervácie
        $reservation = Reservation::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'user_contact' => $validated['user_contact'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'guests' => $validated['guests'],
            'accept' => false,
        ]);
        
        Mail::to(config('mail.from.address'))                      // admin, z MAIL_FROM_ADDRESS
        ->send(new ReservationAdminMail($reservation));

        Mail::to($reservation->email)                              // customer
        ->send(new ReservationConfirmationEmail($reservation));

        return redirect()->back()->with('success', 'Rezervácia bola úspešne vytvorená.');
    }

    public function adminIndex()
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte oprávnenie na prístup k tejto stránke.');
        }

        $reservations = Reservation::orderBy('date', 'desc')->orderBy('time', 'desc')->get();
        return view('reservation.reservationsAdmin', compact('reservations'));
    }

    public function approve($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte oprávnenie na prístup k tejto stránke.');
        }

        $reservation = Reservation::findOrFail($id);
        $reservation->accept = true;
        $reservation->save();
        
        Mail::to($reservation->email)
        ->send(new ReservationApprovedMail($reservation));

        return redirect()->route('reservation.admin')->with('success', 'Rezervácia bola schválená.');
    }

    public function reject($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin) {
            return redirect()->route('index')->with('error', 'Nemáte oprávnenie na prístup k tejto stránke.');
        }

        $reservation = Reservation::findOrFail($id);

        Mail::to($reservation->email)
        ->send(new ReservationRejectedMail($reservation));

        $reservation->delete();

        return redirect()->route('reservation.admin')->with('success', 'Rezervácia bola odmietnutá a odstránená.');
    }
}
