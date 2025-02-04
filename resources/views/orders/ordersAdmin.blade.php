@extends('layouts.base')

@section('title', "Administrácia objednávok")

@section('content')
    <div class="container" style="margin-top:100px;">
        <h1>Administrácia objednávok</h1>
        <!-- Tlačidlá pre výber, hromadné vymazanie a filter -->
        <div style="margin-bottom:20px;">
            <button id="toggleSelectBtn" class="btn btn-secondary">Vybrať</button>
            <button id="deleteSelectedBtn" class="btn btn-danger" style="display:none;">Vymazať vybrané</button>
            <button id="toggleFilterBtn" class="btn btn-info" style="padding-left: 15px">Filtrovať</button>
        </div>

        <!-- Filter formulár (bez tlačidla "Použiť filter") -->
        <div id="filterOptions" style="display:none; margin-bottom:20px;">
            <form id="filterForm">
                <div>
                    <label for="date_from">Dátum od:</label>
                    <input type="date" id="date_from" name="date_from">
                    <label for="date_to">Dátum do:</label>
                    <input type="date" id="date_to" name="date_to">
                </div>
                <div>
                    <label for="time_from">Čas od:</label>
                    <input type="time" id="time_from" name="time_from">
                    <label for="time_to">Čas do:</label>
                    <input type="time" id="time_to" name="time_to">
                </div>
                <div>
                    <label>Status:</label>
                    <div>
                        <input type="checkbox" id="status_pending" name="status_filter[]" value="PENDING">
                        <label for="status_pending">PENDING</label>
                    </div>
                    <div>
                        <input type="checkbox" id="status_accepted" name="status_filter[]" value="ACCEPTED">
                        <label for="status_accepted">ACCEPTED</label>
                    </div>
                    <div>
                        <input type="checkbox" id="status_declined" name="status_filter[]" value="DECLINED">
                        <label for="status_declined">DECLINED</label>
                    </div>
                    <div>
                        <input type="checkbox" id="status_canceled" name="status_filter[]" value="CANCELED">
                        <label for="status_canceled">CANCELED</label>
                    </div>
                </div>
                <div>
                    <label for="sort_order">Poradie objednávok:</label>
                    <select id="sort_order" name="sort_order">
                        <option value="earliest">Najskoršie</option>
                        <option value="latest">Najstaršie</option>
                    </select>
                </div>
            </form>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th class="select-col" style="display:none;">Vybrať</th>
                <th>ID</th>
                <th>Užívateľ</th>
                <th>Vytvorené<br>(dátum/čas)</th>
                <th>Objednávka<br>(dátum/čas)</th>
                <th>Typ objednávky</th>
                <th>Stav</th>
                <th>Poznámky</th>
                <th>Akcie</th>
            </tr>
            </thead>
            <tbody id="ordersTableBody">
            @foreach($orders as $order)
                <tr id="adminOrderRow{{ $order->id }}">
                    <td class="select-col" style="display:none;">
                        <input type="checkbox" class="order-checkbox" data-id="{{ $order->id }}">
                    </td>
                    <td>{{ $order->id }}</td>
                    <td>
                        {{ ($order->user->name ?? '') . ' ' . ($order->user->surname ?? '') }}<br>
                        @if(!empty($order->user->phone))
                            {{ $order->user->phone }}<br>
                        @endif
                        {{ $order->user->email }}
                    </td>
                    <td>
                        {{ $order->created_date }}<br>{{ $order->created_time }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($order->order_date)->format('d.m.Y') }}<br>{{ \Carbon\Carbon::parse($order->order_time)->format('H:i') }}
                    </td>
                    <td>{{ $order->order_type }}</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        @if($order->notes && trim($order->notes) !== '')
                            <a href="#" class="notes-icon" data-note="{{ $order->notes }}">
                                <i class="bi bi-file-earmark-text"></i>
                            </a>
                            @else
                                &ndash;
                        @endif
                    </td>
                    <td>
                        @if($order->status === 'PENDING')
                            <button class="btn btn-sm btn-success accept-btn" data-id="{{ $order->id }}">Prijať</button>
                            <button class="btn btn-sm btn-warning decline-btn" data-id="{{ $order->id }}">Odmietnuť</button>
                        @endif
                        <button class="btn btn-sm btn-danger delete-single-btn" data-id="{{ $order->id }}">Vymazať</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Text pod tabuľkou s kontaktnými informáciami -->
        <div class="order-info" style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #ccc;">
            Pre informácie o objednávke kontaktujte prevádzku na číslo: <strong>032/649 14 01</strong>.
        </div>
    </div>

    <!-- Modal pre zobrazenie poznámky -->
    <div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" id="notesModalDialog">
            <div class="modal-content" style="background-color: #2a2a2a; color: #fff;">
                <div class="modal-header" style="border-bottom: 1px solid #4CAF50;">
                    <h5 class="modal-title" id="notesModalLabel">Poznámka objednávky</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvoriť" style="filter: invert(1);"></button>
                </div>
                <div class="modal-body" id="notesModalBody" style="padding: 20px; font-size: 0.95rem; line-height: 1.6;">
                    <!-- Dynamicky vložený obsah poznámky -->
                </div>
                <div class="modal-footer" style="border-top: 1px solid #4CAF50;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvoriť</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');

            // Funkcia na viazanie event listenerov na dynamicky generovaný obsah
            function bindEventListeners() {
                // Otvorenie modalu s poznámkou – iba ak obsah poznámky nie je prázdny
                document.querySelectorAll('.notes-icon').forEach(icon => {
                    icon.addEventListener('click', function(e) {
                        e.preventDefault();
                        const noteContent = this.getAttribute('data-note');
                        if (!noteContent || noteContent.trim() === '') return;
                        document.getElementById('notesModalBody').textContent = noteContent;

                        // Nastavíme modal na pozíciu kliknutia s offsetom
                        const modalDialog = document.getElementById('notesModalDialog');
                        modalDialog.style.position = 'absolute';
                        modalDialog.style.top = (e.pageY + 20) + 'px';
                        modalDialog.style.left = (e.pageX + 20) + 'px';

                        // Otvoríme modal cez Bootstrap
                        const modalElement = document.getElementById('notesModal');
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    });
                });

                // Tlačidlá pre zmenu stavu objednávky – Prijať
                document.querySelectorAll('.accept-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const orderId = this.getAttribute('data-id');
                        fetch(`/orders/${orderId}/update-status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: 'ACCEPTED' })
                        })
                            .then(response => {
                                if (!response.ok) throw new Error('Error updating status');
                                return response.json();
                            })
                            .then(data => {
                                const row = document.getElementById('adminOrderRow' + orderId);
                                row.cells[5].innerText = data.order.status;
                                if (row.querySelector('.accept-btn')) row.querySelector('.accept-btn').remove();
                                if (row.querySelector('.decline-btn')) row.querySelector('.decline-btn').remove();
                            })
                            .catch(error => console.error(error));
                    });
                });

                // Tlačidlá pre zmenu stavu objednávky – Odmietnuť
                document.querySelectorAll('.decline-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const orderId = this.getAttribute('data-id');
                        fetch(`/orders/${orderId}/update-status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: 'DECLINED' })
                        })
                            .then(response => {
                                if (!response.ok) throw new Error('Error updating status');
                                return response.json();
                            })
                            .then(data => {
                                const row = document.getElementById('adminOrderRow' + orderId);
                                row.cells[5].innerText = data.order.status;
                                if (row.querySelector('.accept-btn')) row.querySelector('.accept-btn').remove();
                                if (row.querySelector('.decline-btn')) row.querySelector('.decline-btn').remove();
                            })
                            .catch(error => console.error(error));
                    });
                });

                // Jednotlivé vymazanie objednávky
                document.querySelectorAll('.delete-single-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const orderId = this.getAttribute('data-id');
                        if (confirm('Ste si istý, že chcete vymazať túto objednávku?')) {
                            fetch(`/orders/${orderId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                }
                            })
                                .then(response => {
                                    if (response.ok) {
                                        document.getElementById('adminOrderRow' + orderId).remove();
                                    } else {
                                        throw new Error('Error deleting order');
                                    }
                                })
                                .catch(error => console.error(error));
                        }
                    });
                });

                // Listener pre checkboxy – ak aspoň jeden checkbox je zaškrtnutý, zobraz tlačidlo "Vymazať vybrané"
                document.querySelectorAll('.order-checkbox').forEach(chk => {
                    chk.addEventListener('change', function() {
                        const anyChecked = Array.from(document.querySelectorAll('.order-checkbox'))
                            .some(chk => chk.checked);
                        deleteSelectedBtn.style.display = anyChecked ? 'inline-block' : 'none';
                    });
                });
            } // end bindEventListeners

            bindEventListeners();

            // Prepínanie viditeľnosti filtračného formulára
            const toggleFilterBtn = document.getElementById('toggleFilterBtn');
            const filterOptions = document.getElementById('filterOptions');
            toggleFilterBtn.addEventListener('click', function() {
                filterOptions.style.display = (filterOptions.style.display === 'none' || filterOptions.style.display === '') ? 'block' : 'none';
            });

            // Dynamické filtrovanie – automatické odoslanie pri zmene hodnoty vo filtračných inputoch
            const filterInputs = document.querySelectorAll('#filterForm input, #filterForm select');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const formData = new FormData(document.getElementById('filterForm'));
                    const params = new URLSearchParams();
                    for (const [key, value] of formData.entries()) {
                        params.append(key, value);
                    }
                    fetch(`/orders/admin?${params.toString()}`, {
                        headers: { 'Accept': 'application/json' }
                    })
                        .then(response => response.json())
                        .then(data => {
                            const tbody = document.getElementById('ordersTableBody');
                            tbody.innerHTML = '';
                            data.orders.forEach(order => {
                                const row = document.createElement('tr');
                                row.id = 'adminOrderRow' + order.id;
                                row.innerHTML = `
                                <td class="select-col" style="display:${selectVisible ? 'table-cell' : 'none'};">
                                    <input type="checkbox" class="order-checkbox" data-id="${order.id}" style="display:${selectVisible ? 'inline-block' : 'none'};">
                                </td>
                                <td>${order.id}</td>
                                <td>
                                    ${(order.user.name ? order.user.name : '') + ' ' + (order.user.surname ? order.user.surname : '')}<br>
                                    ${order.user.phone ? order.user.phone + '<br>' : ''}${order.user.email}
                                </td>
                                <td>${order.created_date}<br>${order.created_time}</td>
                                <td>${order.order_date}<br>${order.order_time}</td>
                                <td>${order.order_type}</td>
                                <td>${order.status}</td>
                                <td>
                                    ${order.notes ? `<a href="#" class="notes-icon" data-note="${order.notes}">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>` : '&ndash;'}
                                </td>
                                <td>
                                    ${order.status === 'PENDING' ? `<button class="btn btn-sm btn-success accept-btn" data-id="${order.id}">Prijať</button>
                                    <button class="btn btn-sm btn-warning decline-btn" data-id="${order.id}">Odmietnuť</button>` : ''}
                                    <button class="btn btn-sm btn-danger delete-single-btn" data-id="${order.id}">Vymazať</button>
                                </td>
                            `;
                                tbody.appendChild(row);
                            });
                            // Rebind event listenerov pre dynamicky načítaný obsah
                            bindEventListeners();
                        })
                        .catch(error => console.error(error));
                });
            });

            // Prepínanie checkboxov pre výber objednávok
            const toggleSelectBtn = document.getElementById('toggleSelectBtn');
            let selectVisible = false;
            toggleSelectBtn.addEventListener('click', function() {
                selectVisible = !selectVisible;
                document.querySelectorAll('.select-col').forEach(col => {
                    col.style.display = selectVisible ? 'table-cell' : 'none';
                });
                document.querySelectorAll('.order-checkbox').forEach(chk => {
                    chk.style.display = selectVisible ? 'inline-block' : 'none';
                    chk.checked = false;
                });
                deleteSelectedBtn.style.display = 'none';
            });

            // Hromadné vymazanie objednávok
            deleteSelectedBtn.addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.order-checkbox:checked'))
                    .map(chk => chk.getAttribute('data-id'));
                if (selectedIds.length === 0) return;
                if (confirm('Ste si istý, že chcete vymazať vybrané objednávky?')) {
                    selectedIds.forEach(id => {
                        fetch(`/orders/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => {
                                if (response.ok) {
                                    document.getElementById('adminOrderRow' + id).remove();
                                } else {
                                    console.error('Error deleting order with id ' + id);
                                }
                            })
                            .catch(error => console.error(error));
                    });
                }
            });
        });
    </script>
@endsection
