@extends('layouts.base')

@section('title', 'Rezervácie')

@section('content')
    <div class="reservation-form" style="text-align: center; margin-top: 50px;">
        <h1>Rezervácia</h1>
        <p>Prosím, vyplňte nasledujúce údaje:</p>

        <form action="{{ url('/submit-reservation') }}" method="POST">
            @csrf
            <div>
                <label for="name">Meno a priezvisko:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="date">Dátum:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div>
                <label for="time">Čas:</label>
                <input type="time" id="time" name="time" required>
            </div>
            <div>
                <label for="guests">Počet hostí:</label>
                <input type="number" id="guests" name="guests" min="1" required>
            </div>
            <button type="submit">Rezervovať</button>
        </form>
    </div>
@endsection
