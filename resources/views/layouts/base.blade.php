<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nedefinovaný názov')</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @vite('resources/js/app.js') <!-- Vložime Vite pre správne načítanie JS súborov -->
</head>

<body class="bg-gray-900 text-white">

<!-- Navigačný panel s vylepšeným z-index -->
<header class="bg-black bg-opacity-60 shadow-md rounded-lg w-full fixed top-0 left-0 z-50">
    <nav class="max-w-screen-lg mx-auto p-4">
        <ul class="flex justify-between sm:justify-center items-center gap-4">
            <li><a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="logo" class="h-16 w-auto"></a></li>
            <li><a href="{{ url('/') }}" class="text-white font-semibold hover:text-yellow-400 transition duration-200">Hlavná stránka</a></li>
            <li><a href="{{ url('/DailyMenu') }}" class="text-white font-semibold hover:text-yellow-400 transition duration-200">Denné menu</a></li>
            <li><a href="{{ url('/Orders') }}" class="text-white font-semibold hover:text-yellow-400 transition duration-200">Objednávky</a></li>
            <li><a href="{{ url('/Reservations') }}" class="text-white font-semibold hover:text-yellow-400 transition duration-200">Rezervácie</a></li>
            <li><a href="{{ url('/PhotoGallery') }}" class="text-white font-semibold hover:text-yellow-400 transition duration-200">Fotogaléria</a></li>
            <li><a href="{{ url('/Contact') }}" class="text-white font-semibold hover:text-yellow-400 transition duration-200">Kontakt</a></li>
            <!-- Tlačítko pre prihlásenie -->
            <li>
                <a href="{{ route('login') }}" class="text-white hover:text-yellow-400 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m0 0l-4 4m4-4l-4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</header>

<main class="pt-24 pb-8">
    @yield('content')
</main>

<footer class="bg-gray-800 text-white text-center p-4">
    <p>&copy; 2024 Gazdovský dvor. Všetky práva vyhradené.</p>
    <p><a href="{{ url('/privacy') }}" class="text-white hover:underline">Ochrana súkromia</a></p>
</footer>

</body>

</html>
