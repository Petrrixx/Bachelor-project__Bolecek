@extends('layouts.base')

@section('title', "Prihlásenie")

@section('content')
    <div id="login-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-center">Prihlásenie</h1>
            <form id="login-form" action="{{ url('/login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Heslo</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Prihlásiť sa</button>
            </form>

            <div class="text-center mt-3">
                <p>Nemáš účet? <a href="{{ url('/register') }}" class="btn btn-link">Zaregistruj sa</a></p>
            </div>
        </div>
    </div>
@endsection
