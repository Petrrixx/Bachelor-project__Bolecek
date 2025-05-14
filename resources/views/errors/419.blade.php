@extends('layouts.base')

@section('title', '419 - Platnosť relácie vypršala')

@section('content')
    <div class="flex flex-col items-center justify-center h-screen text-center">
        <h1 class="text-6xl font-bold mb-4">419</h1>
        <p class="text-xl mb-6">Zdá sa, že platnosť vašej relácie vypršala alebo nie je platný CSRF token.</p>
        <a href="{{ url()->current() }}" class="btn btn-primary">Obnoviť stránku</a>
    </div>
@endsection
