<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nedefinovaný názov')</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>

<div class="cut-background" style="background-image: url('{{ asset('images/background.png') }}');">
</div>


<header class="top">
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

<main>
    @yield('content')
</main>

<footer>
    <div>
        <p>&copy; 2024 Gazdovský dvor. Všetky práva vyhradené.</p>
        <p><a href="{{ url('/privacy') }}">Ochrana súkromia</a></p>
    </div>
</footer>

</body>
</html>
