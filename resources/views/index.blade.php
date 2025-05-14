@extends('layouts.base')

@section('title', "Hlavná stránka")

@section('content')
    <div class="content text-center mt-8 px-4 sm:px-8">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold">Vitajte na stránke Gazdovského dvora!</h1>
        <!-- <p class="text-lg sm:text-xl md:text-2xl mt-4">Toto je úvodná stránka.</p> -->
    </div>

    <!-- Dokonalá kombinácia príchutí -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 p-4">
                <h2 class="text-2xl font-bold mb-4">Dokonalá kombinácia príchutí</h2>
                <p class="text-lg">
                    Sme mladá reštaurácia s rastúcimi skúsenosťami. Jedlá z domácej aj zahraničnej kuchyne pripravené tímom kuchárov, ktorí v našich hosťoch zanechávajú pocit uspokojenia. Sme radi, keď sa opäť stretávame so spokojnými zákazníkmi a  o to viac sa snažíme byť najlepší
                </p>
            </div>
            <div class="md:w-1/2 p-4 flex justify-center">
                <div class="flex space-x-4">
                    <img src="{{ asset('images/image1.jpg') }}" alt="Obrázok 1" class="w-32 h-32 rounded-full object-cover">
                    <img src="{{ asset('images/image2.jpg') }}" alt="Obrázok 2" class="w-32 h-32 rounded-full object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- Naše služby -->
    <div class="bg-orange-800 bg-opacity-25">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 p-4 relative">
                    <img src="{{ asset('images/background.png') }}" alt="Služby" class="w-full h-auto rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-lg">
                        <p class="text-white text-1xl text-center p-4">
                            Vyberte si zo širokej ponuky našich profesionálnych služieb. Garancia spokojnosti a najvyššej kvality.
                        </p>
                    </div>
                </div>
                <div class="md:w-1/2 p-4 grid grid-cols-2 gap-4">
                    <a href="{{ url('/Orders') }}" class="text-center">
                        <img src="{{ asset('images/delivery.jpg') }}" alt="Rozvoz jedla" class="w-full h-32 object-cover rounded-lg">
                        <p class="mt-2 text-lg font-semibold">Rozvoz jedla</p>
                    </a>
                    <a href="{{ url('/DailyMenu') }}" class="text-center">
                        <img src="{{ asset('images/dmenu.jpg') }}" alt="Jedálny lístok" class="w-full h-32 object-cover rounded-lg">
                        <p class="mt-2 text-lg font-semibold">Jedálny lístok</p>
                    </a>
                    <a href="{{ url('/Reservations') }}" class="text-center">
                        <img src="{{ asset('images/reservation.jpg') }}" alt="Rezervácie" class="w-full h-32 object-cover rounded-lg">
                        <p class="mt-2 text-lg font-semibold">Rezervácie</p>
                    </a>
                    <a href="{{ url('/photo-gallery') }}" class="text-center">
                        <img src="{{ asset('images/gallery.jpg') }}" alt="Fotogaléria" class="w-full h-32 object-cover rounded-lg">
                        <p class="mt-2 text-lg font-semibold">Fotogaléria</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Galéria -->
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Galéria</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <!-- PODLA INTERNETU -->
            @php
                $images = glob(public_path('images/svadby/*.{jpg,png,gif}'), GLOB_BRACE);
                shuffle($images);
                $selectedImages = array_slice($images, 0, 6);
            @endphp
            @foreach ($selectedImages as $image)
                <img src="{{ asset('images/svadby/' . basename($image)) }}" alt="Galéria" class="w-full h-48 object-cover rounded-lg">
            @endforeach
        </div>
    </div>

    <!-- Mapa -->
    <div class="full-width-map">
        <a href="https://www.google.com/maps?q=48.8486284,18.0274924" target="_blank" class="map-link">Otvoriť mapu v Google Maps</a>
        <div id="map2"></div>
    </div>
@endsection

@section('scripts')
    <!-- Leaflet pre mapu *PODLA INTERNETU*-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inicializácia mapy
        const map2 = L.map('map2').setView([48.8486284, 18.0274924], 16); // Súradnice a zoom

        // Pridanie OpenStreetMap vrstvy
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map2);

        // Pridanie značky pre vašu reštauráciu
        const marker = L.marker([48.8486284, 18.0274924]).addTo(map2)
            .bindPopup('Gazdovský dvor<br>Trenčianska Turná')
            .openPopup();
    </script>
@endsection
