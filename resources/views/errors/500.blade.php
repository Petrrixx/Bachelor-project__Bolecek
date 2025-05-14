@extends('layouts.base')

@section('title', '500 - Interná chyba servera')

@section('content')
    <div class="flex flex-col items-center justify-center h-screen text-center">
        <h1 class="text-6xl font-bold mb-4">500</h1>
        <p class="text-xl mb-6">Ospravedlňujeme sa, niečo sa pokazilo na serveri.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Späť na úvod</a>
    </div>
@endsection
