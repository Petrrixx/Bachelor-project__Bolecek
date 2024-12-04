@extends('layouts.base')

@section('title', 'Vytvoriť Používateľa')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Vytvoriť Nového Používateľa</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Chyby:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Meno a priezvisko:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Heslo:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Potvrdiť Heslo:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="contact">Telefónne číslo:</label>
                        <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact') }}">
                        @error('contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="verified" name="verified" value="1" {{ old('verified') ? 'checked' : '' }}>
                        <label class="form-check-label" for="verified">Overený</label>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="isAdmin" name="isAdmin" value="1" {{ old('isAdmin') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isAdmin">Administrátor</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Vytvoriť Používateľa</button>
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
