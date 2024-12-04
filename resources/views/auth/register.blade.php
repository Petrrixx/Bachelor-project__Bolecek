@extends('layouts.base')

@section('title', 'Registrácia')

@section('content')
    <div class="container mt-5" style="padding-top: 140px;">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg" style="border-radius: 10px;">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Registrácia</h3>

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

                        <form action="{{ route('register.submit') }}" method="POST" id="register-form-action">
                            @csrf

                            <!-- Meno -->
                            <div class="form-group mb-3">
                                <label for="name">Meno *</label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

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

                            <!-- Potvrdenie hesla -->
                            <div class="form-group mb-3">
                                <label for="password_confirmation">Potvrdenie hesla *</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="form-control" required>
                            </div>

                            <!-- Kontakt -->
                            <div class="form-group mb-3">
                                <label for="contact">Kontakt</label>
                                <input type="text" name="contact" id="contact"
                                       class="form-control @error('contact') is-invalid @enderror"
                                       value="{{ old('contact') }}">
                                @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Súhlas s podmienkami -->
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms"
                                       {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terms">
                                    Súhlasím s <a href="{{ url('/terms') }}" target="_blank" style="color: #0066cc;">podmienkami použitia</a>
                                </label>
                                @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-block">Registrovať sa</button>
                            </div>
                        </form>

                        <!-- Link na prihlásenie -->
                        <div class="mt-3 text-center">
                            <p>Máte už účet? <a href="{{ route('auth.form', ['type' => 'login']) }}">Prihláste sa</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Validácia e-mailu pri registrácii
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

            // Validácia hesla pri registrácii
            document.getElementById('password').addEventListener('input', function() {
                var password = this.value;
                var errorElement = this.nextElementSibling;
                if (password.length < 6) {
                    errorElement.textContent = 'Heslo musí mať minimálne 6 znakov';
                } else {
                    errorElement.textContent = '';
                }
            });

            // Validácia potvrdenia hesla
            document.getElementById('password_confirmation').addEventListener('input', function() {
                var password = document.getElementById('password').value;
                var confirmPassword = this.value;
                var errorElement = this.nextElementSibling;
                if (password !== confirmPassword) {
                    errorElement.textContent = 'Heslá sa nezhodujú';
                } else {
                    errorElement.textContent = '';
                }
            });

            // Validácia súhlasu s podmienkami
            document.getElementById('terms').addEventListener('change', function() {
                var errorElement = this.parentElement.querySelector('.invalid-feedback');
                if (!this.checked) {
                    errorElement.textContent = 'Musíte súhlasiť s podmienkami použitia.';
                } else {
                    errorElement.textContent = '';
                }
            });
        });
    </script>
@endsection
