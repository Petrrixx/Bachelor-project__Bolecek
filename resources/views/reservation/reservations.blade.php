@extends('layouts.base')

@section('title', 'Rezervácie')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="text-center mb-4">Rezervácia</h1>
                <p class="text-center">Prosím, vyplňte nasledujúce údaje:</p>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('reservation.submit') }}" method="POST" class="reservation-form">
                    @csrf
                    <div class="form-group">
                        <label for="name">Meno a priezvisko:</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email-register">Email:</label>
                        <input type="email" id="email-register" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contact">Telefónne číslo</label>
                        <input type="tel" id="contact" name="contact" class="form-control @error('contact') is-invalid @enderror" placeholder="Zadajte telefónne číslo" value="{{ old('contact') }}">
                        @error('contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date">Dátum:</label>
                        <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Čas -->
                    <div class="form-group">
                        <label for="time">Čas:</label>
                        <input type="time" id="time" name="time" class="form-control @error('time') is-invalid @enderror" value="{{ old('time') }}" required>
                        @error('time')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="guests">Počet hostí:</label>
                        <input type="number" id="guests" name="guests" class="form-control @error('guests') is-invalid @enderror" min="1" value="{{ old('guests') }}" required>
                        @error('guests')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Súhlas s podmienkami -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="terms">Súhlasím s <a href="{{ url('/terms') }}" target="_blank" style="color: #0066cc;">podmienkami použitia</a></label>
                        @error('terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success btn-block">Rezervovať</button>
                    </div>
                </form>

                {{-- Tlačidlo pre adminov --}}
                @auth
                    @if(auth()->user()->isAdmin)
                        <a href="{{ route('reservation.admin') }}" class="btn btn-primary mt-3">Zobraziť rezervácie</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/reservation-validation.js') }}"></script>
@endsection
