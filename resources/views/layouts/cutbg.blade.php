<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nedefinovaný názov')</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">

<div class="cut-background bg-cover bg-center min-h-[300px] w-full absolute top-0 left-0" style="background-image: url('{{ asset('images/background.png') }}');"></div>

<header class="top bg-black bg-opacity-60 shadow-md rounded-lg w-full fixed top-0 left-0 z-10">
    <nav class="max-w-screen-lg mx-auto p-4">
        <ul class="flex justify-center items-center gap-4">
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

</body>
</html>
