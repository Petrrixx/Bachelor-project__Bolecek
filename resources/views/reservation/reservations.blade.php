@extends('layouts.base')

@section('title', "Rezervácie")

@section('content')
    <style>
        .card {
            background-color: #2a2a2a; /* Tmavé pozadie pre kartu */
            border: 1px solid #4CAF50;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
        }
    </style>

    <div class="container mt-5">
        <!--<div class="card"> -->
            <div class="card-body">
                <h1 class="text-center mb-4">Rezervácia</h1>
                <p class="text-center">Prosím, vyplňte nasledujúce údaje:</p>

                @if ($errors->has('guests'))
                    <div class="alert alert-danger">
                        {{ $errors->first('guests') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Tlačidlá pre prechod do zoznamu rezervácií -->
                <div class="text-center mt-3">
                    @auth
                        @if(auth()->user()->isAdmin)
                            <a href="{{ route('reservation.admin') }}" class="btn btn-primary mt-3">Zobraziť rezervácie</a>
                        @endif
                    @endauth
                </div>

                <form action="{{ route('reservation.submit') }}" method="POST" class="reservation-form">
                    @csrf
                    <!-- Meno a priezvisko -->
                    <div class="form-group">
                        <label for="name">Meno a priezvisko:</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- E-mail -->
                    <div class="form-group">
                        <label for="email-register">Email:</label>
                        <input type="email" id="email-register" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Telefónne číslo -->
                    <div class="form-group">
                        <label for="user_contact">Telefónne číslo:</label>
                        <input type="tel" id="user_contact" name="user_contact" class="form-control @error('user_contact') is-invalid @enderror"
                               placeholder="Zadajte telefónne číslo" value="{{ old('user_contact') }}" required>
                        @error('user_contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dátum -->
                    <div class="form-group">
                        <label for="date">Dátum:</label>
                        <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date') }}" required>
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Čas -->
                    <div class="form-group">
                        <label for="time">
                            Čas <small class="text-muted">(10:30 - 14:00 Po-Št, 10:30 - 19:00 Pi-Ne)</small>
                        </label>

                        {{-- budeme meniť min/max cez JavaScript, na začiatku je pole zablokované --}}
                        <input type="time"
                            id="time"
                            name="time"
                            class="form-control @error('time') is-invalid @enderror"
                            disabled   {{-- aktivuje sa až po výbere dátumu --}}
                            value="{{ old('time') }}" required>

                        <small id="timeHelp" class="form-text text-muted">
                            Najprv vyberte dátum.
                        </small>

                        @error('time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Počet hostí -->
                    <div class="form-group">
                        <label for="guests">Počet hostí:</label>
                        <input type="number" id="guests" name="guests" class="form-control @error('guests') is-invalid @enderror"
                               min="1" value="{{ old('guests') }}" required>
                        @error('guests')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Súhlas s podmienkami -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="terms">
                            Súhlasím s <a href="{{ url('/terms') }}" target="_blank" class="terms-link">podmienkami použitia</a>
                        </label>
                        @error('terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success btn-block">Rezervovať</button>
                    </div>
                </form>
            </div>
        <!-- </div>  -->
    </div>
@endsection

@section('scripts')
    <script>
        // Rezervačný JS
        // Zobrazí časové obmedzenia na základe vybraného dátumu
        // a nastaví min/max pre input type="time"
        //
        // Ak je vybratý dátum mimo rozsahu, input sa vyprázdni.
        //
        // Ak je input disabled, tak sa nezobrazí ani error správa.
        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('date');
            const timeInput = document.getElementById('time');
            const help      = document.getElementById('timeHelp');

            if (!dateInput || !timeInput || !help) {
                console.warn('Rezervačný JS: chýba niektorý z elementov');
                return;
            }

            const updateTimeLimits = () => {
                const dateVal = dateInput.value;                // "2025-05-13" alebo ""
                if (!dateVal) {
                    timeInput.disabled = true;
                    help.textContent   = 'Najprv vyberte dátum.';
                    return;
                }

                // UTC polnoc, aby sa neplietla lokálna zóna
                const day = new Date(dateVal + 'T00:00:00Z').getUTCDay(); // 0 = Ned.
                const min = '10:30';
                const max = [5, 6, 0].includes(day) ? '19:00' : '14:00';

                timeInput.min      = min;
                timeInput.max      = max;
                timeInput.disabled = false;
                help.textContent   = `Možný čas ${min} – ${max}`;

                // Ak už je v poli stará hodnota mimo rozsahu, vyčistíme ju
                if (timeInput.value && (timeInput.value < min || timeInput.value > max)) {
                    timeInput.value = '';
                }
            };

            dateInput.addEventListener('change', updateTimeLimits);
            updateTimeLimits();      // spustí sa hneď, kvôli old('date')
        });
    </script>
@endsection
