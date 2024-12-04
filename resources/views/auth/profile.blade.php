@extends('layouts.base')

@section('title', 'Môj Profil')

@section('content')
    <div class="container mt-5" style="padding-top: 140px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg" style="border-radius: 10px;">
                    <div class="card-header text-center">
                        <h3>Môj Profil</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="full_name">Celé meno *</label>
                                <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $user->full_name) }}" required>
                                @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail *</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contact">Telefónne číslo</label>
                                <input type="tel" name="contact" id="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact', $user->contact) }}">
                                @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <h5>Zmena Hesla</h5>

                            <div class="form-group">
                                <label for="password">Nové Heslo</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Zadajte nové heslo">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Potvrdenie Nového Hesla</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Potvrďte nové heslo">
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Uložiť Zmeny</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
