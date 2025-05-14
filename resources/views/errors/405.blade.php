@extends('layouts.base') 

@section('title', 'Metóda nie je povolená')
@section('description', 'Požadovaná HTTP metóda nie je pre túto adresu povolená.')

@section('content')
    <section class="flex flex-col items-center justify-center h-screen text-center">
        <p class="text-xl mb-6">
            Požadovaná adresa nie je povolená.<br>
            Skúste to, prosím, inak alebo sa vráťťe na <a href="{{ url('/') }}" class="underline">úvodnú stránku</a>.
        </p>
    </section>
@endsection
