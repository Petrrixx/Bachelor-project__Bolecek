<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Nedefinovaný názov')</title>

    <!-- Načítanie Vite súborov -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Vlastný CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-gray-900 text-white">
<!-- Pozadie s obrázkom a overlayom -->
<div class="bg-container">
    <img src="{{ asset('images/background2.png') }}" alt="Pozadie" class="bg-img">
    <div class="bg-overlay"></div>
</div>

<!-- Panel pre prihlásenie, profil a správu užívateľov -->
@if (!request()->is('auth*') && !request()->is('profile*'))
    @if (Auth::check())
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" id="logout-btn">Odhlásiť sa</button>
        </form>
        <a href="{{ route('profile.show') }}" id="profile-btn">Môj profil</a>
        @if (Auth::user()->isAdmin)
            <a href="{{ route('admin.users.index') }}" id="users-management-btn">Správa Užívateľov</a>
        @endif
    @else
        <a href="{{ route('auth.auth', ['type' => 'login']) }}" id="login-btn">Prihlásiť sa</a>
    @endif
@endif

<!-- Navigačný panel -->
<header>
    <nav>
        <ul>
            <li><a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="logo"></a></li>
            <li><a href="{{ url('/') }}">Hlavná stránka</a></li>
            <li><a href="{{ url('/DailyMenu') }}">Jedálny lístok</a></li>
            <li><a href="{{ url('/Orders') }}">Objednávky</a></li>
            <li><a href="{{ url('/Reservations') }}">Rezervácie</a></li>
            <li><a href="{{ url('/PhotoGallery') }}">Fotogaléria</a></li>
            <li><a href="{{ url('/Contact') }}">Kontakt</a></li>
        </ul>
    </nav>
</header>

<main class="pt-24 pb-8">
    @yield('content')
</main>

<footer class="footer bg-gray-800 text-white text-center p-4">
    <p>&copy; 2025 Gazdovský dvor. Všetky práva vyhradené.</p>
    <p>
        <a href="{{ url('/privacy') }}">Ochrana osobných údajov</a> |
        <a href="{{ url('/terms') }}">Podmienky použitia</a>
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const footer = document.querySelector('.footer');
        const body = document.body;
        const html = document.documentElement;

        function checkScroll() {
            const scrollTop = window.scrollY || html.scrollTop || body.scrollTop;
            const windowHeight = window.innerHeight || html.clientHeight || body.clientHeight;
            const documentHeight = Math.max(
                body.scrollHeight,
                body.offsetHeight,
                html.clientHeight,
                html.scrollHeight,
                html.offsetHeight
            );

            // Zobrazenie footeru, ak sme doscrollovali na spodok
            if (scrollTop + windowHeight >= documentHeight - 50) {
                footer.classList.add('visible');
            } else {
                footer.classList.remove('visible');
            }
        }

        window.addEventListener('scroll', checkScroll);
        checkScroll(); // Skontrolovať pri načítaní stránky
    });
</script>
@yield('scripts')
</body>
</html>
