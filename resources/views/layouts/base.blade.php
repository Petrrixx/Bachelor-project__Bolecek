<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nedefinovaný názov')</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Dynamické pozadie v style tagu -->
    <style>
        /* Dynamické pozadie pre index */
        @if (request()->is('/'))
            .background-full {
            background-image: url('{{ asset('images/background2.JPEG') }}');
            background-size: cover;
            background-position: center;
            height: 100vh; /* Celá obrazovka */
            width: 100%;
            position: absolute; /* Aby pozadie nebolo ovplyvnené obsahom */
            top: 0;
            left: 0;
            z-index: -1; /* Aby pozadie bolo pod všetkým ostatným */
            filter: blur(8px); /* Rozmazanie obrázka */
        }

        .background-overlay {
            background: rgba(0, 0, 0, 0.5); /* Polopriesvitná čierna vrstva */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Aby vrstva bola pod obsahom */
        }
        @else
            .background-full {
            background-image: url('{{ asset('images/background.png') }}');
            background-size: cover;
            background-position: center 40%;  /* Posúvame obrázok tak, aby bol viditeľný zospodu */
            height: 180px;  /* Zmenená výška na 180px */
            width: 100%;
            position: absolute; /* Aby pozadie nebolo ovplyvnené obsahom */
            top: 0;
            left: 0;
            z-index: -1; /* Aby pozadie bolo pod všetkým ostatným */
        }
        @endif

        /* Zabezpečenie, že footer bude vždy na spodku */
        html, body {
            height: 100%;  /* Celková výška stránky bude 100% */
            margin: 0;
            display: flex;
            flex-direction: column;  /* Flexbox, aby sme mohli footer prichytiť na spodok */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #ffffff;
            line-height: 1.6;
        }

        main {
            flex: 1;  /* Toto zabezpečí, že hlavný obsah zaberá všetok dostupný priestor */
            padding: 20px;
            overflow: auto;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            font-size: 0.8rem;
            margin-top: auto;  /* Toto zabezpečí, že footer bude na spodku */
        }

        /* Modálne okno štýl */
        .modal {
            display: none; /* Skryté podľa predvolieb */
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Polopriesvitný tmavý pozadie */
            justify-content: center;
            align-items: center;  /* Vertikálne aj horizontálne vycentrované */
            display: flex; /* Používame flexbox na centerovanie */
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px; /* Určuje maximálnu šírku okna */
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn-submit {
            width: 100%;
            padding: 10px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #444;
        }

        /* Tlačítko prihlásenia */
        #login-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #login-btn:hover {
            background-color: #333;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">

<!-- Pozadie celé na obrazovke -->
<div class="background-full"></div>

<!-- Polopriesvitná čierna vrstva pre lepšiu čitateľnosť textu -->
@if (request()->is('/'))
    <div class="background-overlay"></div>
@endif

<!-- Tlačítko prihlásenia, ktoré bude fixované v pravom hornom rohu -->
@auth
    <li>
        <a href="{{ url('/profile') }}" class="text-white hover:text-yellow-400 transition duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m0 0l-4 4m4-4l-4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </a>
    </li>
@else
    <button id="login-btn" class="fixed top-4 right-4 text-white bg-black hover:bg-gray-700 py-2 px-4 rounded-full transition duration-200">
        Prihlásiť sa
    </button>
@endauth

<!-- Navigačný panel -->
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
        </ul>
    </nav>
</header>

<main class="pt-24 pb-8">
    @yield('content')
</main>

<footer class="bg-gray-800 text-white text-center p-4">
    <p>&copy; 2024 Gazdovský dvor. Všetky práva vyhradené.</p>
    <p><a href="{{ url('/privacy') }}" class="text-white hover:underline">Ochrana osobných údajov</a> | <a href="{{ url('/terms') }}" class="text-white hover:underline">Podmienky použitia</a></p>
</footer>

<!-- Modálne okno pre prihlásenie -->
<div id="login-modal" class="modal">
    <div class="modal-content">
        <span id="close-login-modal" class="close-btn">&times;</span>
        <h2 class="text-center text-xl font-semibold">Prihlásiť sa</h2>
        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm">Email</label>
                <input type="email" name="email" id="email" class="input-field" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm">Heslo</label>
                <input type="password" name="password" id="password" class="input-field" required>
            </div>
            <button type="submit" class="btn-submit">Prihlásiť sa</button>
        </form>
    </div>
</div>

<!-- JavaScript pre modálne okná -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loginBtn = document.getElementById('login-btn');
        const loginModal = document.getElementById('login-modal');
        const closeLoginModalBtn = document.getElementById('close-login-modal');

        // Ukáž modálne okno pri kliknutí na tlačidlo "Prihlásiť sa"
        loginBtn.addEventListener('click', function() {
            loginModal.style.display = 'flex';  // Zobraz modálne okno
        });

        // Skryť modálne okno pri kliknutí na tlačidlo "Zatvoriť"
        closeLoginModalBtn.addEventListener('click', function() {
            loginModal.style.display = 'none';  // Skry modálne okno
        });

        // Skryť modálne okno pri kliknutí mimo okna
        window.addEventListener('click', function(event) {
            if (event.target === loginModal) {
                loginModal.style.display = 'none';  // Skry modálne okno
            }
        });
    });
</script>

</body>
</html>
