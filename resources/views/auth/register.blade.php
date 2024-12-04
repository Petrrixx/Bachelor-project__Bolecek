@extends('layouts.base')

@section('title', "Registrácia")

@section('content')
    <div id="register-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-center">Registrácia</h1>
            <form action="{{ route('/register.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Meno</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

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

                <button type="submit" class="btn btn-primary w-100">Registrovať sa</button>
            </form>

            <div class="text-center mt-3">
                <p>Už máš účet? <a href="{{ route('/login') }}" class="btn btn-link">Prihlás sa</a></p>
            </div>
        </div>
    </div>
@endsection
