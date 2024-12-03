@extends('layouts.base')

@section('title', "Hlavná stránka")

@section('content')
    <!-- Hlavný obsah s pozadím -->
    <div class="background-image bg-cover bg-center w-full min-h-screen relative" style="background-image: url('{{ asset('images/background2.JPEG') }}');">
        <!-- Klikateľný obrázok na ľavej strane -->
        <a href="{{ url('/Contact') }}" class="clickable-image absolute left-4 top-3/4 transform -translate-y-1/2 md:left-10 md:w-32 lg:w-40 transition duration-300 hover:scale-105">
            <img src="{{ asset('images/location.png') }}" alt="Kontakt" class="w-full h-auto">
        </a>
        <!-- Klikateľný obrázok na pravej strane -->
        <a href="{{ url('/DailyMenu') }}" class="clickable-image absolute right-4 top-3/4 transform -translate-y-1/2 md:right-10 md:w-32 lg:w-40 transition duration-300 hover:scale-105">
            <img src="{{ asset('images/menu.png') }}" alt="Denné menu" class="w-full h-auto">
        </a>
    </div>

    <!-- Obsah pod pozadím -->
    <div class="content text-center mt-8 px-4 sm:px-8">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold">Vitajte na stránke Gazdovského dvora!</h1>
        <p class="text-lg sm:text-xl md:text-2xl mt-4">Toto je úvodná stránka.</p>
    </div>
@endsection
