@extends('layouts.base')

@section('title', request()->get('type') == 'register' ? 'Registrácia' : 'Prihlásenie')

@section('content')
    <div class="container mt-5" style="padding-top: 140px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Bootstrap triedy: bg-light text-dark -->
                <div class="card bg-light text-dark shadow-lg" style="border-radius: 10px;">
                    <div class="card-body">
                        <!-- Prihlásenie -->
                        <div id="login-form" style="display: {{ request()->get('type') == 'register' ? 'none' : 'block' }};">
                            <h3 class="text-center mb-4">Prihlásenie</h3>
                            <form action="{{ route('login.submit') }}" method="POST" id="login-form-action">
                                @csrf
                                <div class="form-group">
                                    <label for="email">E-mail *</label>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="Zadajte email"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Heslo *</label>
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Zadajte heslo" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check text-left">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">Zapamätať si ma (W.I.P)</label>
                                </div>

                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary btn-block">Prihlásiť sa</button>
                                </div>
                            </form>
                            <!--
                            <div class="mt-3 text-center">
                                <p>Nemáte účet?
                                    <a href="{{ route('auth.auth', ['type' => 'register']) }}">Zaregistrujte sa</a>
                                </p>
                            </div>
                            -->
                        </div>

                        <!-- Registrácia -->
                        <div id="register-form" style="display: {{ request()->get('type') == 'register' ? 'block' : 'none' }};">
                            <h3 class="text-center mb-4">Registrácia</h3>
                            <form action="{{ route('register.submit') }}" method="POST" id="register-form-action">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Meno *</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Zadajte celé meno"
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email-register">E-mail *</label>
                                    <input type="email" name="email" id="email-register"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="Zadajte email"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Telefónne číslo -->
                                <div class="form-group">
                                    <label for="contact">Telefónne číslo</label>
                                    <input type="tel" name="contact" id="contact"
                                           class="form-control @error('contact') is-invalid @enderror"
                                           placeholder="Zadajte telefónne číslo"
                                           value="{{ old('contact') }}">
                                    @error('contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Heslo -->
                                <div class="form-group">
                                    <label for="password-register">Heslo *</label>
                                    <input type="password" name="password" id="password-register"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Zadajte heslo" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Potvrdenie hesla -->
                                <div class="form-group">
                                    <label for="password_confirmation">Potvrdenie Hesla *</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           placeholder="Potvrďte heslo" required>
                                    @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Súhlas s podmienkami -->
                                <div class="form-check text-left">
                                    <input type="checkbox" class="form-check-input" id="terms" name="terms"
                                           {{ old('terms') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="terms">
                                        Súhlasím s
                                        <a href="{{ url('/terms') }}" target="_blank" style="color: #0066cc;">
                                            podmienkami použitia
                                        </a>
                                    </label>
                                    @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-success btn-block">Registrovať sa</button>
                                </div>
                            </form>
                            <div class="mt-3 text-center">
                                <p>Máte už účet?
                                    <a href="{{ route('auth.auth', ['type' => 'login']) }}">Prihláste sa</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/register-validation.js') }}"></script>
@endsection
