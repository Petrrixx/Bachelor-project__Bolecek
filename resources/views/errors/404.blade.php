@extends('layouts.base')

@section('title', 'Stránka sa nenašla')

@section('content')
    <div class="flex flex-col items-center justify-center h-screen text-center">
        <h1 class="text-6xl font-bold mb-4">Požadovaná stránka nebola nájdená.</h1>
        <a href="{{ url('/') }}" class="btn btn-primary">Späť na úvod</a>
    </div>
@endsection