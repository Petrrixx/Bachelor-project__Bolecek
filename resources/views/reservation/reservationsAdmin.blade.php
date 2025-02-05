@extends('layouts.base')

@section('title', 'Správa Rezervácií')

@section('content')
    <style>
        /* Štýly pre tabuľku rezervácií - NIEKTORE CASTI PODLA INTERNETU (MAM POPISANE)*/
        table.table-striped {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px; /* Vertikálny odstup medzi riadkami */
        }

        table.table-striped th,
        table.table-striped td {
            padding: 12px 15px; /* Horizontálny a vertikálny odstup v bunkách */
            background-color: #2a2a2a; /* Farba pozadia pre bunky */
            border-radius: 8px; /* Zaoblené rohy pre bunky */
        }

        table.table-striped th {
            background-color: #333; /* Iná farba pozadia pre hlavičku tabuľky */
            color: #fff; /* Farba textu v hlavičke */
            text-align: left; /* Zarovnanie textu v hlavičke */
        }

        table.table-striped tbody tr {
            background-color: #1a1a1a; /* Farba pozadia pre riadky */
        }

        table.table-striped tbody tr:hover {
            background-color: #444; /* Farba pozadia pri prechode myšou */
        }

        /* Štýly pre tlačidlá v tabuľkách */
        table.table-striped .btn {
            margin-right: 5px; /* Horizontálny odstup medzi tlačidlami */
        }

        /* Štýly pre oddelenie hlavičky od tela tabuľky */
        table.table-striped thead th {
            border-bottom: 2px solid #555;
        }

        /* Štýly pre dlhé texty v bunkách */
        table.table-striped td {
            word-wrap: break-word;
        }

        /* Štýly pre tlačidlá na schválenie/odmietnutie */
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* Štýly pre badge */
        .badge-success {
            background-color: #28a745;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        /* Štýly pre oddelenie tlačidiel */
        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Správa Rezervácií</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($reservations->isEmpty())
                    <p>Žiadne rezervácie nie sú k dispozícii.</p>
                @else
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Meno</th>
                            <th>Email</th>
                            <th>Dátum</th>
                            <th>Čas</th>
                            <th>Počet hostí</th>
                            <th>Schválené</th>
                            <th>Akcie</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->id }}</td>
                                <td>{{ $reservation->name }}</td>
                                <td>{{ $reservation->email }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->date)->format('d.m.Y') }}</td>
                                <td>{{ $reservation->formatted_time }}</td>
                                <td>{{ $reservation->guests }}</td>
                                <td>{{ $reservation->accept ? 'Áno' : 'Nie' }}</td>
                                <td>
                                    @if(!$reservation->accept)
                                        <div class="action-buttons">
                                            <!-- Schváliť Rezerváciu -->
                                            <form action="{{ route('reservation.approve', $reservation->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Ste si istý, že chcete schváliť túto rezerváciu?')">Schváliť</button>
                                            </form>

                                            <!-- Odmietnuť Rezerváciu -->
                                            <form action="{{ route('reservation.reject', $reservation->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Ste si istý, že chcete odmietnuť a odstrániť túto rezerváciu?')">Odmietnuť</button>
                                            </form>
                                        </div>
                                    @else
                                        <!-- Rezervácia je už schválená -->
                                        <span class="badge badge-success">Schválené</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection

{{--@section('scripts')--}}
{{--    <script>--}}
{{--    </script>--}}
{{--@endsection--}}
