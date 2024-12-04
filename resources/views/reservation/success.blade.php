@extends('layouts.base')

@section('title', 'Rezervácia úspešná')

@section('content')
    <div class="reservation-success">
        <h1>Rezervácia bola úspešne uložená!</h1>
        <p>Ďakujeme za vašu rezerváciu! Čoskoro vás kontaktujeme pre ďalšie informácie.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Späť na domovskú stránku</a>
    </div>
@endsection
