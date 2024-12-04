@extends('layouts.base')

@section('title', 'Správa používateľov')

@section('content')
    <style>
        /* Nové štýly pre lepšie rozostupy v tabuľkách */
        table.table-striped {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        table.table-striped th,
        table.table-striped td {
            padding: 12px 15px; /* Horizontálny a vertikálny odstup v bunkách */
            background-color: #2a2a2a; /* Farba pozadia */
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

        /* Štýly pre tlačidlo na vytvorenie používateľa */
        .create-user-btn {
            margin-bottom: 20px; /* Odstup spod tabuľky */
        }
    </style>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Zoznam používateľov</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(Auth::user()->isAdmin)
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success create-user-btn">Vytvoriť Používateľa</a>
                @endif

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Meno</th>
                        <th>Email</th>
                        <th>Telefón</th>
                        <th>Overený</th>
                        <th>Administrátor</th>
                        <th>Akcie</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->contact }}</td>
                            <td>{{ $user->verified ? 'Áno' : 'Nie' }}</td>
                            <td>{{ $user->isAdmin ? 'Áno' : 'Nie' }}</td>
                            <td>
                                @if(Auth::user()->isAdmin)
                                    <!-- Odkazy na úpravu alebo vymazanie používateľa -->
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Upraviť</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Ste si istý, že chcete vymazať tohto používateľa?')">Vymazať</button>
                                    </form>
                                @else
                                    <span class="badge badge-secondary">Bez akcií</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

{{--@section('scripts')--}}
{{--    <script>--}}
{{--    </script>--}}
{{--@endsection--}}
