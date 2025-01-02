<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nedefinovaný názov')</title>

    <!-- Načítanie Vite súborov -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;  /* Flexbox, aby sme mohli footer prichytiť na spodok */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #ffffff;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Hlavný obsah */
        main {
            flex: 1;  /* Toto zabezpečí, že hlavný obsah zaberá všetok dostupný priestor */
            padding: 20px;
            overflow: auto;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            font-size: 0.8rem;
            margin-top: auto;  /* Toto zabezpečí, že footer bude na spodku */
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Hlavný navigačný panel */
        header {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.6);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            border-radius: 5px;
            z-index: 1000;
            width: 90%;
            max-width: 1200px; /* Maximálna šírka pre navigačný panel */
        }

        /* Navigačný panel - list */
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            padding: 0;
            margin: 0;
        }

        /* Navigačný odkaz */
        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            padding: 8px 16px;
            transition: background 0.3s, color 0.3s;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }

        nav ul li a img {
            height: 40px; /* Zmenšená výška loga */
            width: auto;
            vertical-align: middle;
        }

        /* Hover efekt na navigačných odkazoch */
        nav ul li a:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffd700;
        }

        /* Tlačidlá prihlásenia, profilu a správy užívateľov */
        #login-btn, #profile-btn, #users-management-btn, #logout-btn {
            position: fixed;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            z-index: 1001; /* Vyšší z-index ako header */
            font-size: 0.9rem;
        }

        #login-btn {
            background-color: #000;
            color: #fff;
            right: 20px;
            top: 20px;
        }

        #profile-btn {
            background-color: #28a745;
            color: #fff;
            right: 30px; /* Upravená pozícia */
            top: 20px;
        }

        #users-management-btn {
            background-color: #007bff;
            color: #fff;
            right: 20px;
            top: 150px;
        }

        #logout-btn {
            background-color: #dc3545;
            color: #fff;
            right: 20px; /* Upravená pozícia */
            top: 90px;
        }

        #login-btn:hover {
            background-color: #333;
        }

        #profile-btn:hover {
            background-color: #218838;
        }

        #users-management-btn:hover {
            background-color: #0069d9;
        }

        #logout-btn:hover {
            background-color: #c82333;
        }

        /* Modal */
        .modal {
            display: none; /* Skryté pred zobrazením */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Polopriesvitný tmavý pozadie */
            justify-content: center;
            align-items: center;
            z-index: 1050; /* Vyšší z-index ako tlačidlá */
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Formulárové Polia a Karty */
        .card {
            background-color: #1a1a1a;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .card-body {
            padding: 20px;
        }

        .form-control {
            height: 45px;
            border-radius: 5px;
            border: 1px solid #4CAF50;
            background-color: #2a2a2a;
            color: #fff;
            font-size: 1rem;
            padding-left: 15px;
            margin-bottom: 15px;
            width: 100%;
        }

        .form-control:focus {
            border-color: #FF5733;
            box-shadow: 0 0 10px rgba(255, 87, 51, 0.6);
            background-color: #333;
        }

        /* Submit Button */
        .btn-block {
            border-radius: 5px;
            font-size: 16px;
        }

        /* Chybová Správa */
        .invalid-feedback {
            color: red;
            font-size: 0.875rem;
        }

        /* Pre "Súhlasím s" */
        .form-check-label {
            color: #fff !important;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .form-check-label a {
            color: #FF5733;
            text-decoration: none;
        }

        .form-check-label a:hover {
            text-decoration: underline;
        }

        /* Odkazy */
        a {
            text-decoration: none;
            color: #0066cc;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive Návrh */
        @media (max-width: 768px) {
            header {
                width: 95%;
                padding: 10px 10px;
            }

            nav ul {
                flex-direction: column;
                gap: 10px;
            }

            /* Upravené pozície tlačidiel */
            #profile-btn {
                right: 80px;
                top: 20px;
            }

            #users-management-btn {
                right: 20px;
                top: 110px;
            }

            #logout-btn {
                right: 200px;
                top: 90px;
            }
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

<!-- Podmienka na zobrazenie tlačidiel prihlásenia, profilu a správy užívateľov -->
@if (!request()->is('auth*') && !request()->is('profile*'))
    @if (Auth::check())
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" id="logout-btn">
                Odhlásiť sa
            </button>
        </form>
        <a href="{{ route('profile.show') }}" id="profile-btn">
            Môj profil
        </a>

        @if (Auth::user()->isAdmin)
            <a href="{{ route('admin.users.index') }}" id="users-management-btn">
                Správa Užívateľov
            </a>
        @endif
    @else
        <a href="{{ route('auth.auth', ['type' => 'login']) }}" id="login-btn">
            Prihlásiť sa
        </a>
    @endif
@endif

<!-- Navigačný panel -->
<header>
    <nav>
        <ul>
            <li><a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="logo"></a></li>
            <li><a href="{{ url('/') }}">Hlavná stránka</a></li>
            <li><a href="{{ url('/DailyMenu') }}">Denné menu</a></li>
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

<footer class="bg-gray-800 text-white text-center p-4">
    <p>&copy; 2025 Gazdovský dvor. Všetky práva vyhradené.</p>
    <p><a href="{{ url('/privacy') }}">Ochrana osobných údajov</a> | <a href="{{ url('/terms') }}">Podmienky použitia</a></p>
</footer>

@yield('scripts')

</body>
</html>
