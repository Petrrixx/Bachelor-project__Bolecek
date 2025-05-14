@extends('layouts.base')

@section('title', 'Prihlásenie')

@section('content')
    <div class="container mt-5" style="padding-top: 140px;">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg" style="border-radius: 10px;">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Prihlásenie</h3>

                        <!-- Zobrazenie chýb -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Chyba!</strong> Skontrolujte si vstupné údaje.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login.submit') }}" method="POST" id="login-form-action">
                            @csrf

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label for="email">Email *</label>
                                <input type="email" name="email" id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Heslo -->
                            <div class="form-group mb-3">
                                <label for="password">Heslo *</label>
                                <input type="password" name="password" id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Zapamätať si ma -->
                            <div class="form-group form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Zapamätať si ma</label>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Prihlásiť sa</button>
                            </div>
                        </form>

                        <!-- Link na registráciu -->
                         <!-- ZATIAL PREC
                        <div class="mt-3 text-center">
                            <p>Nemáte účet? <a href="{{ route('auth.form', ['type' => 'register']) }}">Zaregistrujte sa</a></p>
                        </div>
                        --> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Validácia e-mailu pri prihlásení
            document.getElementById('email').addEventListener('input', function() {
                var email = this.value;
                var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                var errorElement = this.nextElementSibling;
                if (!emailPattern.test(email)) {
                    errorElement.textContent = 'Neplatný formát e-mailu';
                } else {
                    errorElement.textContent = '';
                }
            });

            // Validácia hesla pri prihlásení
            document.getElementById('password').addEventListener('input', function() {
                var password = this.value;
                var errorElement = this.nextElementSibling;
                if (password.length < 6) {
                    errorElement.textContent = 'Heslo musí mať minimálne 6 znakov';
                } else {
                    errorElement.textContent = '';
                }
            });
        });
    </script>
@endsection
