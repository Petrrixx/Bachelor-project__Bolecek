@extends('layouts.base')

@section('title', 'Upraviť profil')

@section('content')
    <style>
        .edit-profile-container {
            margin-top: 50px;
            padding: 20px;
            background-color: #2a2a2a;
            border-radius: 10px;
        }

        .edit-profile-container h1 {
            margin-bottom: 30px;
            color: #fff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            background-color: #1a1a1a;
            color: #ddd;
            border: 1px solid #444;
            border-radius: 5px;
        }

        .form-control:focus {
            background-color: #1a1a1a;
            color: #fff;
            border-color: #555;
        }

        .btn-primary, .btn-danger {
            margin-right: 10px;
        }

        .delete-account-btn {
            margin-top: 30px;
        }

        .invalid-feedback {
            display: block;
            color: #ff6b6b;
        }
    </style>

    <div class="container">
        <div class="edit-profile-container">
            <h1>Upraviť profil</h1>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Meno a priezvisko:</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contact">Telefónne číslo:</label>
                    <input type="text" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact', $user->contact) }}">
                    @error('contact')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Uložiť zmeny</button>
                <a href="{{ route('profile.show') }}" class="btn btn-secondary">Späť</a>
            </form>

            <form action="{{ route('profile.destroy') }}" method="POST" class="delete-account-btn">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Ste si istý, že chcete vymazať svoj účet? Táto akcia je nevratná.')">Vymazať účet</button>
            </form>
        </div>
    </div>
@endsection

{{--@section('scripts')--}}
{{--    <script>--}}
{{--    </script>--}}
{{--@endsection--}}
