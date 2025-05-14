@extends('layouts.base')

@section('title', '429 - Príliš veľa požiadaviek')

@section('content')
    <div class="flex flex-col items-center justify-center h-screen text-center">
        <h1 class="text-6xl font-bold mb-4">429</h1>
        <p class="text-xl mb-6">Poslal/a si príliš veľa požiadaviek, skúste to prosím o chvíľu.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">Späť</a>
    </div>
@endsection
