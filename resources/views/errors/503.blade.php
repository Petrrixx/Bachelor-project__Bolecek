@extends('layouts.base')

@section('title', '503 - Stránka nedostupná')

@section('content')
    <div class="flex flex-col items-center justify-center h-screen text-center">
        <h1 class="text-6xl font-bold mb-4">503</h1>
        <p class="text-xl mb-6">Aplikácia je momentálne v režime údržby. Vráťte sa prosím neskôr.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Späť na úvod</a>
    </div>
@endsection
<!-- Všetky error stránky sú predpripravené, boli robene na rýchlo, bez zistovania si presných info o prístupe na ne. -->