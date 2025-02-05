@extends('layouts.base')

@section('title', "Administrácia menu")

@section('content')
    <style>
        input, select {
            color: #000 !important;
        }
    </style>

    <div class="container my-4">
        <h1>Administrácia denného menu</h1>

        <!-- Miesto pre notifikácie (úspech alebo chyba) -->
        <div id="notification" style="display: none;"></div>

        <table class="table" id="menu-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Jedlo</th>
                <th>Príloha</th>
                <th>Obloha</th>
                <th>Alergény</th>
                <th>Akcie</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr data-id="{{ $item->id }}">
                    <td class="item-id">{{ $item->id }}</td>
                    <td class="item-food_name">{{ $item->food_name }}</td>
                    <td class="item-priloha_name">
                        @if(isset($prilohy))
                            @php
                                $selectedPriloha = collect($prilohy)->firstWhere('id', $item->priloha_name);
                            @endphp
                            {{ $selectedPriloha ? $selectedPriloha->name : $item->priloha_name }}
                        @else
                            {{ $item->priloha_name }}
                        @endif
                    </td>
                    <td class="item-obloha_name">
                        @if(isset($oblohy))
                            @php
                                $selectedObloha = collect($oblohy)->firstWhere('id', $item->obloha_name);
                            @endphp
                            {{ $selectedObloha ? $selectedObloha->name : $item->obloha_name }}
                        @else
                            {{ $item->obloha_name }}
                        @endif
                    </td>
                    <td class="item-all_allergens">{{ $item->all_allergens }}</td>
                    <td class="actions">
                        <button class="btn btn-primary btn-sm edit-btn">Upraviť</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $item->id }}">Vymazať</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    {{-- Ak sú k dispozícii zoznamy pre prílohy a oblohy, prevedieme ich do JS --}}
    @if(isset($prilohy))
        <script>
            window.prilohyData = @json($prilohy);
        </script>
    @endif

    @if(isset($oblohy))
        <script>
            window.oblohyData = @json($oblohy);
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Funkcia pre zobrazenie notifikácie
            function showNotification(message, type = 'success') {
                var notification = document.getElementById('notification');
                notification.innerText = message;
                notification.className = 'alert alert-' + (type === 'success' ? 'success' : 'danger');
                notification.style.display = 'block';
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 3000);
            }

            // Funkcia, ktorá prepne riadok do editačného módu
            function enableEditing(row) {
                var food_name = row.querySelector('.item-food_name').innerText.trim();
                var priloha_text = row.querySelector('.item-priloha_name').innerText.trim();
                var obloha_text = row.querySelector('.item-obloha_name').innerText.trim();
                var all_allergens = row.querySelector('.item-all_allergens').innerText.trim();

                // Nahradíme textové hodnoty inputmi
                row.querySelector('.item-food_name').innerHTML = '<input type="text" class="form-control edit-food_name" value="'+ food_name +'">';

                // Ak máme zoznam príloh, použijeme select, inak input
                if (typeof window.prilohyData !== 'undefined') {
                    var prilohaSelect = '<select class="form-control edit-priloha_name">';
                    window.prilohyData.forEach(function(priloha) {
                        var selected = (priloha.name.trim() === priloha_text) ? 'selected' : '';
                        prilohaSelect += '<option value="'+ priloha.id +'" '+ selected +'>'+ priloha.name +'</option>';
                    });
                    prilohaSelect += '</select>';
                    row.querySelector('.item-priloha_name').innerHTML = prilohaSelect;
                } else {
                    row.querySelector('.item-priloha_name').innerHTML = '<input type="text" class="form-control edit-priloha_name" value="'+ priloha_text +'">';
                }

                // Podobne pre oblohu
                if (typeof window.oblohyData !== 'undefined') {
                    var oblohaSelect = '<select class="form-control edit-obloha_name">';
                    window.oblohyData.forEach(function(obloha) {
                        var selected = (obloha.name.trim() === obloha_text) ? 'selected' : '';
                        oblohaSelect += '<option value="'+ obloha.id +'" '+ selected +'>'+ obloha.name +'</option>';
                    });
                    oblohaSelect += '</select>';
                    row.querySelector('.item-obloha_name').innerHTML = oblohaSelect;
                } else {
                    row.querySelector('.item-obloha_name').innerHTML = '<input type="text" class="form-control edit-obloha_name" value="'+ obloha_text +'">';
                }

                row.querySelector('.item-all_allergens').innerHTML = '<input type="text" class="form-control edit-all_allergens" value="'+ all_allergens +'">';

                // Zmeniť akcie – namiesto "Upraviť" zobrazíme "Uložiť" a "Zrušiť"
                row.querySelector('.actions').innerHTML = '<button class="btn btn-success btn-sm save-btn">Uložiť</button> ' +
                    '<button class="btn btn-secondary btn-sm cancel-btn">Zrušiť</button>';
            }

            // Funkcia, ktorá riadok vráti späť do módu zobrazenia (po úspešnej aktualizácii)
            function disableEditing(row, data) {
                row.querySelector('.item-food_name').innerText = data.food_name;
                row.querySelector('.item-priloha_name').innerText = data.priloha_name_display || data.priloha_name;
                row.querySelector('.item-obloha_name').innerText = data.obloha_name_display || data.obloha_name;
                row.querySelector('.item-all_allergens').innerText = data.all_allergens;
                row.querySelector('.actions').innerHTML = '<button class="btn btn-primary btn-sm edit-btn">Upraviť</button> ' +
                    '<button class="btn btn-danger btn-sm delete-btn" data-id="'+ data.id +'">Vymazať</button>';
            }

            // Delegovanie udalostí – klik na tlačítko "Upraviť"
            document.querySelector('#menu-table tbody').addEventListener('click', function(e) {
                if (e.target.classList.contains('edit-btn')) {
                    var row = e.target.closest('tr');
                    enableEditing(row);
                }
            });

            // Delegovanie udalostí – klik na tlačítko "Zrušiť"
            document.querySelector('#menu-table tbody').addEventListener('click', function(e) {
                if (e.target.classList.contains('cancel-btn')) {
                    // Ak klikneme Zrušiť, obnovíme stránku (alebo by ste mohli uchovať pôvodné hodnoty a obnoviť len daný riadok)
                    location.reload();
                }
            });

            // Delegovanie udalostí – klik na tlačítko "Uložiť" (AJAX update)
            document.querySelector('#menu-table tbody').addEventListener('click', function(e) {
                if (e.target.classList.contains('save-btn')) {
                    var row = e.target.closest('tr');
                    var id = row.getAttribute('data-id');
                    var food_name = row.querySelector('.edit-food_name').value;
                    var all_allergens = row.querySelector('.edit-all_allergens').value;

                    var data = {
                        food_name: food_name,
                        all_allergens: all_allergens
                    };

                    fetch("{{ url('/menu') }}/" + id, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('Chyba pri aktualizácii.');
                            }
                            return response.json();
                        })
                        .then(function(result) {
                            // Predpokladáme, že disableEditing prepne riadok späť do normálneho zobrazenia
                            disableEditing(row, {
                                id: id,
                                food_name: result.food_name,
                                priloha_name: result.priloha_name,
                                // Ak máš k dispozícii display hodnotu, môžeš ju použiť:
                                priloha_name_display: result.priloha_name_display,
                                obloha_name: result.obloha_name,
                                obloha_name_display: result.obloha_name_display,
                                all_allergens: result.all_allergens
                            });
                            showNotification('Zmena bola uložená.', 'success');
                        })
                        .catch(function(error) {
                            showNotification(error.message, 'error');
                        });
                }
            });

            // Delegovanie udalostí – klik na tlačítko "Vymazať" (AJAX delete)
            document.querySelector('#menu-table tbody').addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-btn')) {
                    if (!confirm('Ste si istý, že chcete vymazať toto jedlo?')) {
                        return;
                    }
                    var btn = e.target;
                    var id = btn.getAttribute('data-id');
                    fetch("{{ url('/menu') }}/" + id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        })
                    })
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('Chyba pri mazaní.');
                            }
                            return response.json();
                        })
                        .then(function(result) {
                            // Odstránime riadok z tabuľky
                            var row = btn.closest('tr');
                            row.parentNode.removeChild(row);
                            showNotification('Jedlo bolo vymazané.', 'success');
                        })
                        .catch(function(error) {
                            showNotification(error.message, 'error');
                        });
                }
            });
        });
    </script>
@endsection
