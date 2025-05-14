@extends('layouts.base')

@section('title', "Kontakty")

@section('content')
    <div class="contact-container">
        {{-- flash správa po úspešnom odoslaní --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="contact-content">
            <!-- Obrázky na ľavej strane -->
            <div class="contact-images">
                <img src="{{ asset('images/nightPhoto.jpeg') }}" alt="Obrázok 1" class="contact-image">
                <img src="{{ asset('images/insidePhoto.jpeg') }}" alt="Obrázok 2" class="contact-image">
            </div>

            <!-- Kontaktné informácie -->
            <div class="contact-info">
                <div class="contact-left">
                    <h2>ADRESA PREVÁDZKY:</h2>
                    <p>Reštaurácia Gazdovský Dvor</p>
                    <p>Bánovská 1004</p>
                    <p>913 21 Trenčianska Turná</p>
                    <p>Prevádzka: 032/649 14 01</p>
                    <p>Mobil: +421 903 746 851</p>
                    <p>E-mail: gazdovskydvor@gazdovskydvor.eu</p>
                    <p>WEB: gazdovskydvor.sk</p>
                </div>

                <div class="contact-right">
                    <h2>FAKTURAČNÉ ÚDAJE:</h2>
                    <p>Gazdovský Dvor</p>
                    <p>Bánovská 1004</p>
                    <p>913 21 Trenčianska Turná</p>
                    <p>_______________________</p>
                    <p>IČO: 00 000 000</p>
                    <p>IČ DPH: SK0000000000</p>
                </div>
            </div>
        </div>

        <!-- Kontaktný formulár -->
        <div class="contact-form">
            <h2>Máte otázku? Neváhajte nás kontaktovať...</h2>
            <form id="contactForm"
                  method="POST"
                  action="{{ route('contact.submit') }}"
                  enctype="multipart/form-data"
            >
                @csrf

                <div class="form-group">
                    <label for="name">Meno</label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                    >
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="surname">Priezvisko</label>
                    <input type="text"
                           id="surname"
                           name="surname"
                           value="{{ old('surname') }}"
                           required
                    >
                    @error('surname')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Telefón</label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           required
                    >
                    @error('phone')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                    >
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="subject">Predmet</label>
                    <input type="text"
                           id="subject"
                           name="subject"
                           value="{{ old('subject') }}"
                           required
                    >
                    @error('subject')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message">Text správy</label>
                    <textarea id="message"
                              name="message"
                              rows="5"
                              required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="attachment">Priložiť prílohu</label>
                    <input type="file"
                           id="attachment"
                           name="attachment"
                    >
                    @error('attachment')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">ODOSLAŤ</button>
            </form>
        </div>

        <!-- Mapa -->
        <div class="contact-map">
            <h2>Kde nás nájdete</h2>
            <div id="map"></div>
            <a href="https://www.google.com/maps?q=48.8486284,18.0274924"
               target="_blank"
               class="map-link"
            >
                Otvoriť mapu v Google Maps
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Leaflet pre mapu -->
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const map = L.map('map').setView([48.8486284, 18.0274924], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.marker([48.8486284, 18.0274924])
             .addTo(map)
             .bindPopup('Gazdovský dvor<br>Trenčianska Turná')
             .openPopup();
        });
    </script>
@endsection
