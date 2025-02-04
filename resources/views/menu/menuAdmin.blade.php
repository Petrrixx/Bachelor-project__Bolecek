@extends('layouts.base')

@section('title', "Administrácia menu")

@section('content')
    <div class="container my-4">
        <h1>Administrácia denného menu</h1>
        <a href="{{ route('menu.create') }}" class="btn btn-success mb-3">Pridať nové jedlo</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Jedlo</th>
                <th>Príloha</th>
                <th>Obloha</th>
                <th>Alergény</th>
                <th>Akcie</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->food_name }}</td>
                    <td>{{ $item->priloha_name }}</td>
                    <td>{{ $item->obloha_name }}</td>
                    <td>{{ $item->all_allergens }}</td>
                    <td>
                        <a href="{{ route('menu.edit', $item->id) }}" class="btn btn-primary btn-sm">Upraviť</a>
                        <form action="{{ route('menu.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ste si istý, že chcete vymazať toto jedlo?')">Vymazať</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
