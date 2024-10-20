<!DOCTYPE html>
<html lang="sk">
@extends('layouts.base')

@section('title', "Hlavná stránka")

@section('content')

    <div class="background-image" style="background-image: url('{{ asset('images/background2.JPEG') }}');">
        <a href="{{ url('/Contact') }}" class="clickable-image left">
            <img src="{{ asset('images/location.png') }}" alt="Kontakt" />
        </a>
        <a href="{{ url('/DailyMenu') }}" class="clickable-image right">
            <img src="{{ asset('images/menu.png') }}" alt="Denné menu" />
        </a>
    </div>


    {{-- Obsah pod pozadím --}}
    <div class="content">
        <h1>Vitajte na stránke Gazdovského dvora!</h1>
        <p>Toto je úvodná stránka.</p>
    </div>

@endsection
