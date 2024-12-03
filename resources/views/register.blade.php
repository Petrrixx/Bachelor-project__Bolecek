@extends('layouts.base')

@section('title', "Registrácia")

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl text-center">Registrácia</h1>
        <form id="register-form" class="mt-6" action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div>
                <label for="full_name" class="block">Celé meno</label>
                <input type="text" id="full_name" name="full_name" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <div class="mt-4">
                <label for="email" class="block">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded" required>
            </div>

            <div class="mt-4">
                <label for="phone_number" class="block">Mobilné číslo</label>
                <input type="tel" id="phone_number" name="phone_number" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mt-4">
                <label for="password" class="block">Heslo</label>
                <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded" required>
                <small class="text-gray-600">Heslo musí obsahovať minimálne 6 znakov, vrátane veľkého a malého písmena.</small>
            </div>

            <div class="mt-4">
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Registrovať</button>
            </div>
        </form>
    </div>

    {{-- JavaScript pre validáciu formulára --}}
    <script>
        document.getElementById('register-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const full_name = document.getElementById('full_name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const phone_number = document.getElementById('phone_number').value;

            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!email.match(emailPattern)) {
                alert("Prosím, zadajte platný email.");
                return;
            }

            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
            if (!password.match(passwordPattern)) {
                alert("Heslo musí obsahovať minimálne 6 znakov, vrátane veľkého a malého písmena.");
                return;
            }

            if (phone_number && !/^\+?[0-9]{10,15}$/.test(phone_number)) {
                alert("Mobilné číslo musí mať formát: +421901234567.");
                return;
            }
            this.submit();
        });
    </script>
@endsection
