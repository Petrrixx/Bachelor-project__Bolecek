@extends('layouts.base')

@section('title', "Objednávky")

@section('content')
    <div class="container">
        <!--<h1>Objednávky</h1> -->

        <!-- Navigačné tlačidlá -->
        <div style="margin-bottom: 20px;">
            <a href="{{ route('orders.user.index') }}" class="btn btn-secondary">Moje objednávky</a>
            @if(Auth::check() && Auth::user()->isAdmin)
                <a href="{{ route('orders.admin.index') }}" class="btn btn-warning" style="padding-left: 200px">Administrácia objednávok</a>
            @endif
        </div>

        @if (!Auth::check())
            <p>Pre objednávanie je potrebné sa prihlásiť. <a href="{{ route('auth.auth', ['type' => 'login']) }}">Prihlásiť sa</a></p>
        @else
            <div class="order-box" style="margin-top: 20px;">
                <h2>Vytvoriť objednávku</h2>
                <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <!-- Výber dátumu (dnes alebo zajtra) -->
                    <div>
                        <label for="order_date">Dátum objednávky:</label>
                        <select name="order_date" id="order_date" class="form-control">
                            <option value="{{ \Carbon\Carbon::today()->toDateString() }}">
                                {{ \Carbon\Carbon::today()->format('d.m.Y') }} (Dnes)
                            </option>
                            <option value="{{ \Carbon\Carbon::today()->addDay()->toDateString() }}">
                                {{ \Carbon\Carbon::today()->addDay()->format('d.m.Y') }} (Zajtra)
                            </option>
                        </select>
                    </div>

                    <!-- Výber času (10:00 - 15:00) -->
                    <div>
                        <label for="order_time">Čas doručenia (10:00 - 15:00):</label>
                        <input type="time" id="order_time" name="order_time" class="form-control" min="10:00" max="15:00" required>
                    </div>

                    <!-- Výber typu objednávky -->
                    <div>
                        <label for="order_type">Typ objednávky:</label>
                        <select name="order_type" id="order_type" class="form-control" required>
                            <option value="osobny_odber">Osobný odber</option>
                            <option value="rozvoz">Rozvoz</option>
                        </select>
                    </div>

                    <!-- Textové pole pre poznámky -->
                    <div>
                        <label for="notes">Poznámky:</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Čo si prajete objednať.. pre ponuku pozrite 'Jedálny lístok'."></textarea>
                    </div>

                    <!-- Skryté pole so statusom -->
                    <input type="hidden" name="status" value="PENDING">

                    <!-- Tlačidlo na odoslanie objednávky -->
                    <button type="submit" class="btn btn-primary">Poslať objednávku</button>
                </form>

                <!-- Hlásenia pre AJAX odpoveď -->
                <div id="successMessage" style="display:none; margin-top: 10px;" class="alert alert-success">
                    Objednávka bola odoslaná úspešne.
                </div>
                <div id="errorMessage" style="display:none; margin-top: 10px;" class="alert alert-danger">
                    Nastala chyba pri odosielaní objednávky.
                </div>

                <!-- Kontaktná informácia -->
                <div class="order-info" style="margin-top: 20px; font-size: 0.9rem;">
                    <p>Pre informácie o objednávke kontaktujte prevádzku na číslo: <strong>032/649 14 01</strong></p>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderForm = document.getElementById('orderForm');
            if (orderForm) {
                orderForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(orderForm);

                    fetch(orderForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                        .then(response => response.ok ? response.json() : Promise.reject())
                        .then(data => {
                            document.getElementById('successMessage').style.display = 'block';
                            document.getElementById('errorMessage').style.display = 'none';
                            orderForm.reset();
                        })
                        .catch(error => {
                            console.error(error);
                            document.getElementById('errorMessage').style.display = 'block';
                            document.getElementById('successMessage').style.display = 'none';
                        });
                });
            }
        });
    </script>
@endsection
