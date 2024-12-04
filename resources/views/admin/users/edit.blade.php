@extends('layouts.base')

@section('title', 'Upraviť používateľa')

@section('content')
    <style>
        /* Pridaj CSS, ak potrebuješ ďalšie úpravy */
    </style>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Upraviť Používateľa</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Meno a priezvisko:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contact">Telefónne číslo:</label>
                        <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact', $user->contact) }}">
                        @error('contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="verified" name="verified" value="1" {{ $user->verified ? 'checked' : '' }}>
                        <label class="form-check-label" for="verified">Overený</label>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="isAdmin" name="isAdmin" value="1" {{ $user->isAdmin ? 'checked' : '' }}>
                        <label class="form-check-label" for="isAdmin">Administrátor</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Uložiť zmeny</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Späť</a>
                </form>
            </div>
        </div>
    </div>
@endsection

{{--@section('scripts')--}}
{{--    <script>--}}
{{--    </script>--}}
{{--@endsection--}}
