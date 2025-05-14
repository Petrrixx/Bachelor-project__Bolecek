@extends('layouts.base')

@section('title', 'Prístup zamietnutý')

@section('content')
  <div class="flex flex-col items-center justify-center h-screen text-center">
    <h1 class="text-6xl font-bold mb-4">403</h1>
    <p class="text-xl mb-6">Nemáte oprávnenie na prístup k tejto stránke.</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Späť</a>
  </div>
@endsection
