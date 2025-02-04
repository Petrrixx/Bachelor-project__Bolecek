@extends('layouts.base')

@section('title', "Moje rezervácie")

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Moje rezervácie</h1>
        @if($reservations->isEmpty())
            <p class="text-center">Nemáte žiadne rezervácie.</p>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Meno a priezvisko</th>
                    <th>Email</th>
                    <th>Telefón</th>
                    <th>Dátum</th>
                    <th>Čas</th>
                    <th>Počet hostí</th>
                    <th>Stav</th>
                    <th>Akcie</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reservations as $reservation)
                    <tr id="reservationRow{{ $reservation->id }}">
                        <td>{{ $reservation->id }}</td>
                        <td>{{ $reservation->name }}</td>
                        <td>{{ $reservation->email }}</td>
                        <td>{{ $reservation->contact }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->date)->format('d.m.Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
                        <td>{{ $reservation->guests }}</td>
                        <td>{{ $reservation->status }}</td>
                        <td>
                            @if($reservation->status !== 'CANCELED')
                                <button class="btn btn-sm btn-danger cancel-reservation-btn" data-id="{{ $reservation->id }}">
                                    Zrušiť
                                </button>
                                @else
                                    &ndash;
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            document.querySelectorAll('.cancel-reservation-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const reservationId = this.getAttribute('data-id');
                    if (confirm('Ste si istý, že chcete zrušiť túto rezerváciu?')) {
                        fetch(`/reservations/${reservationId}/cancel`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.ok ? response.json() : Promise.reject())
                            .then(data => {
                                const row = document.getElementById('reservationRow' + reservationId);
                                row.children[7].innerText = 'CANCELED';
                            })
                            .catch(error => console.error(error));
                    }
                });
            });
        });
    </script>
@endsection
