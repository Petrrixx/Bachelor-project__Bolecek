@extends('layouts.base')

@section('title', "Moje objednávky")

@section('content')
    <div class="container" style="margin-top:100px;">
        <h1>Moje objednávky</h1>
        @if($orders->isEmpty())
            <p>Žiadne objednávky.</p>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Dátum</th>
                    <th>Čas</th>
                    <th>Typ objednávky</th>
                    <th>Status</th>
                    <th>Poznámky</th>
                    <th>Akcie</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr id="orderRow{{ $order->id }}">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->order_date->format('d.m.Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->order_time)->format('H:i') }}</td>
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
                            <button class="btn btn-sm btn-warning edit-btn"
                                    data-id="{{ $order->id }}"
                                    data-order_date="{{ $order->order_date }}"
                                    data-order_time="{{ $order->order_time }}"
                                    data-order_type="{{ $order->order_type }}"
                                    data-notes="{{ $order->notes }}">Upraviť</button>
                            <button class="btn btn-sm btn-danger cancel-btn" data-id="{{ $order->id }}">Zrušiť</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="order-info" style="margin-top:20px; text-align:center; font-size:0.9rem; color:#ccc;">
                Pre informácie o objednávke kontaktujte prevádzku na číslo: <strong>032/649 14 01</strong>.
            </div>
        @endif
    </div>

    <!-- Custom modal pre zobrazenie poznámky -->
    <div id="notesModal" class="custom-modal" style="display:none; position:fixed; z-index:3000;">
        <div class="custom-modal-content" style="max-width:400px; padding:15px; background:#2a2a2a; border:1px solid #4CAF50; border-radius:8px;">
            <button class="custom-modal-close" style="position:absolute; top:5px; right:5px; font-size:2rem; background:transparent; border:none; color:#fff;">&times;</button>
            <div id="notesModalBody" style="padding-top:30px; font-size:0.95rem;"></div>
        </div>
    </div>

    <!-- Custom modal pre rýchlu úpravu objednávky -->
    <div id="editModal" class="custom-modal" style="display:none; position:fixed; z-index:3000;">
        <div class="custom-modal-content" style="max-width:400px; padding:15px; background:#2a2a2a; border:1px solid #4CAF50; border-radius:8px;">
            <button class="custom-modal-close" style="position:absolute; top:5px; right:5px; font-size:2rem; background:transparent; border:none; color:#fff;">&times;</button>
            <h3 style="color:#ffd700; margin-bottom:15px;">Upraviť objednávku</h3>
            <form id="editOrderForm">
                @csrf
                <input type="hidden" name="order_id" id="edit_order_id">
                <div>
                    <label for="edit_order_date">Dátum objednávky:</label>
                    <select name="order_date" id="edit_order_date" class="form-control">
                        <option value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            {{ \Carbon\Carbon::today()->format('d.m.Y') }} (Dnes)
                        </option>
                        <option value="{{ \Carbon\Carbon::today()->addDay()->toDateString() }}">
                            {{ \Carbon\Carbon::today()->addDay()->format('d.m.Y') }} (Zajtra)
                        </option>
                    </select>
                </div>
                <div>
                    <label for="edit_order_time">Čas objednávky (10:00-15:00):</label>
                    <input type="time" id="edit_order_time" name="order_time" class="form-control" min="10:00" max="15:00" required>
                </div>
                <div>
                    <label for="edit_order_type">Typ objednávky:</label>
                    <select name="order_type" id="edit_order_type" class="form-control">
                        <option value="Osobný odber">Osobný odber</option>
                        <option value="Rozvoz">Rozvoz</option>
                    </select>
                </div>
                <div>
                    <label for="edit_notes">Poznámky:</label>
                    <textarea id="edit_notes" name="notes" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-sm btn-warning" style="margin-top:10px;">Uložiť zmeny</button>
            </form>
            <div id="editSuccessMessage" style="display:none;" class="alert alert-success" style="margin-top:10px;">
                Objednávka bola upravená.
            </div>
            <div id="editErrorMessage" style="display:none;" class="alert alert-danger" style="margin-top:10px;">
                Chyba pri úprave objednávky.
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Funkcia na viazanie event listenerov pre dynamicky generovaný obsah
            function bindEventListeners() {
                // Otváranie modálu s poznámkou
                document.querySelectorAll('.notes-icon').forEach(icon => {
                    icon.addEventListener('click', function(e) {
                        e.preventDefault();
                        const noteContent = this.getAttribute('data-note');
                        if (!noteContent || noteContent.trim() === '') return;
                        document.getElementById('notesModalBody').textContent = noteContent;
                        const notesModal = document.getElementById('notesModal');
                        // Nastavíme modál v blízkosti miesta kliknutia s offsetom 10px
                        notesModal.style.top = (e.pageY + 10) + 'px';
                        notesModal.style.left = (e.pageX + 10) + 'px';
                        notesModal.style.display = 'block';
                    });
                });
                // Zatváranie modálu (poznámka aj edit)
                document.querySelectorAll('.custom-modal-close').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.closest('.custom-modal').style.display = 'none';
                    });
                });
                window.addEventListener('click', function(e) {
                    document.querySelectorAll('.custom-modal').forEach(modal => {
                        if (e.target === modal) {
                            modal.style.display = 'none';
                        }
                    });
                });
                // Otváranie modálu pre úpravu objednávky
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        const orderId = this.getAttribute('data-id');
                        const orderDate = this.getAttribute('data-order_date');
                        const orderTime = this.getAttribute('data-order_time');
                        const notes = this.getAttribute('data-notes');
                        const orderType = this.getAttribute('data-order_type');
                        document.getElementById('edit_order_id').value = orderId;
                        document.getElementById('edit_order_date').value = orderDate;
                        document.getElementById('edit_order_time').value = orderTime;
                        document.getElementById('edit_order_type').value = orderType;
                        document.getElementById('edit_notes').value = notes;
                        const editModal = document.getElementById('editModal');
                        // Nastavenie modálu v blízkosti miesta kliknutia
                        editModal.style.top = (e.pageY + 10) + 'px';
                        editModal.style.left = (e.pageX + 10) + 'px';
                        editModal.style.display = 'block';
                    });
                });
            } // end bindEventListeners

            bindEventListeners();

            // AJAX - úprava objednávky
            document.getElementById('editOrderForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const orderId = document.getElementById('edit_order_id').value;
                const formData = new FormData(this);
                fetch(`/orders/${orderId}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.ok ? response.json() : Promise.reject())
                    .then(data => {
                        document.getElementById('editSuccessMessage').style.display = 'block';
                        // Aktualizácia riadku tabuľky
                        const row = document.getElementById('orderRow' + orderId);
                        row.children[1].innerText = data.order.order_date;
                        row.children[2].innerText = data.order.order_time;
                        row.children[3].innerText = data.order.order_type;
                        row.children[4].innerText = data.order.status;
                        row.children[5].innerHTML = data.order.notes ? `<a href="#" class="notes-icon" data-note="${data.order.notes}"><i class="bi bi-file-earmark-text"></i></a>` : '&ndash;';
                        setTimeout(() => {
                            document.getElementById('editSuccessMessage').style.display = 'none';
                            document.getElementById('editModal').style.display = 'none';
                        }, 1500);
                    })
                    .catch(error => {
                        console.error(error);
                        document.getElementById('editErrorMessage').style.display = 'block';
                    });
            });

            // AJAX - zrušenie objednávky
            document.querySelectorAll('.cancel-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-id');
                    if (confirm('Ste si istý, že chcete zrušiť objednávku?')) {
                        fetch(`/orders/${orderId}/cancel`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.ok ? response.json() : Promise.reject())
                            .then(data => {
                                const row = document.getElementById('orderRow' + orderId);
                                row.children[4].innerText = 'CANCELED';
                            })
                            .catch(error => console.error(error));
                    }
                });
            });
        });
    </script>
@endsection
