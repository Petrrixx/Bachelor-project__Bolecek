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
<!-- NA TERAZ ZAKOMENTOVANE
@if (!request()->is('auth*') && !request()->is('profile*'))
    @if (Auth::check())
        <a href="{{ route('profile.show') }}" id="profile-btn">
            <i class="bi bi-person"></i> Profil
        </a>

        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" id="logout-btn">
                <i class="bi bi-box-arrow-right"></i> Odhlásiť sa
            </button>
        </form>

        @if (Auth::user()->isAdmin)
            <a href="{{ route('admin.users.index') }}" id="users-management-btn">
                <i class="bi bi-gear"></i> User Databáza
            </a>
        @endif
    @else
        <a href="{{ route('auth.auth', ['type' => 'login']) }}" id="login-btn">Prihlásiť sa</a>
    @endif
@endif
-->


<!-- Navigačný panel -->
<header>
    <nav>
        <ul>
            <li><a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="logo"></a></li>
            <li><a href="{{ url('/') }}">Hlavná stránka</a></li>
            <li>
                <a 
                    href="https://www.superobed.sk/gazdovsky-dvor/dennemenu" 
                    target="_blank" 
                    rel="noopener noreferrer"
                >
                    Denné menu
                </a>
            </li>
            <li><a href="{{ url('/DailyMenu') }}">Jedálny lístok</a></li>
            <li><a href="{{ url('/Orders') }}">Objednávky</a></li>
            <li><a href="{{ url('/Reservations') }}">Rezervácie</a></li>
            <li><a href="{{ url('/photo-gallery') }}">Fotogaléria</a></li>
            <li><a href="{{ url('/Contact') }}">Kontakt</a></li>
        </ul>
    </nav>
</header>

<main class="pt-24 pb-8">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

<footer class="footer bg-gray-800 text-white text-center p-4" style="position: fixed; bottom: -100px; width: 100%; transition: bottom 0.3s;">
    <p>&copy; 2025 Gazdovský dvor. Všetky práva vyhradené.</p>
    <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
        <a href="{{ url('/privacy') }}">Ochrana osobných údajov</a> |
        <a href="{{ url('/terms') }}">Podmienky použitia</a>
    </div>
</footer>

<button id="scroll-footer-btn" style="position: fixed; bottom: -100px; right: 20px; background-color: #6c757d; color: white; border: none; border-radius: 50%; width: 50px; height: 50px; display: flex; justify-content: center; align-items: center; cursor: pointer; z-index: 1000; transition: bottom 0.3s, opacity 0.3s;">
    <i class="bi bi-person" style="font-size: 1.5rem;"></i>
</button>

<!-- Načítanie Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const footer = document.querySelector('.footer');
        const scrollFooterBtn = document.getElementById('scroll-footer-btn');
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

            // Zobrazenie footeru a tlačidla, ak sme doscrollovali na spodok
            if (scrollTop + windowHeight >= documentHeight - 50) {
                footer.style.bottom = '0';
                scrollFooterBtn.style.bottom = '10px'; // Vo vnútri footeru
            } else {
                footer.style.bottom = '-100px';
                scrollFooterBtn.style.bottom = '-100px';
            }
        }

        scrollFooterBtn.addEventListener('click', function () {
            window.location.href = "{{ route('auth.auth', ['type' => 'login']) }}";
        });

        window.addEventListener('scroll', checkScroll);
        checkScroll(); // Skontrolovať pri načítaní stránky
    });
</script>
@yield('scripts')
</body>
</html>
