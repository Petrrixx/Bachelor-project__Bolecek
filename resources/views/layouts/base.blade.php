<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nedefinovaný názov')</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- css úprava pretože v styles.css mi asset nevie nájsť obrázky -->
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
    </style>
</head>
<body class="bg-gray-900 text-white">

<div class="background-full"></div>

<!-- Polopriesvitná čierna vrstva pre lepšiu čitateľnosť textu -->
@if (request()->is('/'))
    <div class="background-overlay"></div>
@endif

<!-- Tlačítko prihlásenia, ktoré bude fixované v pravom hornom rohu -->
@auth
    <!-- Ak je používateľ prihlásený, zobrazí sa tlačidlo na odhlásenie -->
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="text-white font-semibold hover:text-yellow-400 transition duration-200">
            Odhlásiť sa
        </button>
    </form>
@else
    <!-- Tlačidlo prihlásenia pre neprihlásených používateľov -->
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
    <p><a href="{{ url('/privacy') }}" class="text-white hover:underline">Ochrana súkromia</a></p>
</footer>

<!-- JavaScript pre modálne okná a validáciu -->
<script>
    const loginBtn = document.getElementById('login-btn');
    const loginModal = document.getElementById('login-modal');
    const registerModal = document.getElementById('register-modal');

    // Zobrazenie modálneho okna pre prihlásenie
    loginBtn.addEventListener('click', function() {
        loginModal.classList.remove('hidden');
    });

    // Funkcia na skrytie okna po kliknutí mimo neho
    window.onclick = function(event) {
        if (event.target === loginModal || event.target === registerModal) {
            loginModal.classList.add('hidden');
            registerModal.classList.add('hidden');
        }
    }

    // Prihlásenie a registrácia - kontrola prázdnych polí
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Validácia
        if (!email || !password) {
            alert('Prosím, vyplňte všetky polia.');
            return;
        }
        this.submit();
    });
</script>

</body>
</html>
