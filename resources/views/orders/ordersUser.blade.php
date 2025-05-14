@extends('layouts.base')

@section('title', "Moje objednávky")
<!-- Zobrazujeme len objednávky používateľa, ktorý je prihlásený, MOMENTALNE SA NEPOUZIVA -->
@section('content')
    <div class="container" style="margin-top:100px;">
        <h1>Moje objednávky</h1>
        @if($orders->isEmpty())
            <p>Žiadne objednávky.</p>
        @else
            <table class="table">
                <thead>
                <tr>
                    <!-- Zobrazujeme len: Dátum, Čas, Typ objednávky, Stav, Poznámky, Akcie -->
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
                        <td>{{ $order->order_date->format('d.m.Y') }}</td>
                        <td>{{ $order->formatted_order_time }}</td>
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
                                    data-order_date="{{ $order->order_date->toDateString() }}"
                                    data-order_time="{{ $order->formatted_order_time }}"
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

    <!-- Modal pre zobrazenie poznámky -->
    <div id="notesModal" class="custom-modal" style="display:none; position:fixed; z-index:3000;">
        <div class="custom-modal-content" style="max-width:400px; padding:15px; background:#2a2a2a; border:1px solid #4CAF50; border-radius:8px;">
            <button class="custom-modal-close" style="position:absolute; top:5px; right:5px; font-size:2rem; background:transparent; border:none; color:#fff;">&times;</button>
            <div id="notesModalBody" style="padding-top:30px; font-size:0.95rem;"></div>
        </div>
    </div>

    <!-- Modal pre úpravu objednávky (používateľ) -->
    <div id="editModal" class="custom-modal" style="display:none; position:fixed; z-index:3000;">
        <div class="custom-modal-content" style="max-width:400px; padding:15px; background:#2a2a2a; border:1px solid #4CAF50; border-radius:8px;">
            <button class="custom-modal-close" style="position:absolute; top:5px; right:5px; font-size:2rem; background:transparent; border:none; color:#fff;">&times;</button>
            <h3 style="color:#ffd700; margin-bottom:15px;">Upraviť objednávku</h3>
            <!-- Dátum nie je editovateľný, pretože používateľ nemôže meniť dátum objednávky -->
            <form id="editOrderForm">
                @csrf
                <input type="hidden" name="order_id" id="edit_order_id">
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
            <div id="editSuccessMessage" class="alert alert-success" style="display:none; margin-top:10px;">
                Objednávka bola upravená.
            </div>
            <div id="editErrorMessage" class="alert alert-danger" style="display:none; margin-top:10px;">
                Chyba pri úprave objednávky.
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function bindEventListeners() {
                // Otváranie modálu s poznámkou
                document.querySelectorAll('.notes-icon').forEach(icon => {
                    icon.addEventListener('click', function(e) {
                        e.preventDefault();
                        const noteContent = this.getAttribute('data-note');
                        if (!noteContent || noteContent.trim() === '') return;
                        document.getElementById('notesModalBody').textContent = noteContent;
                        const notesModal = document.getElementById('notesModal');
                        notesModal.style.top = (e.pageY + 10) + 'px';
                        notesModal.style.left = (e.pageX + 10) + 'px';
                        notesModal.style.display = 'block';
                    });
                });

                // Zatváranie modálov
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
                        const orderTime = this.getAttribute('data-order_time');
                        const notes = this.getAttribute('data-notes');
                        const orderType = this.getAttribute('data-order_type');

                        // Kontrola stavu – ak je stav ACCEPTED alebo DECLINED, úprava nie je povolená
                        const statusText = this.closest('tr').children[3].innerText.trim();
                        if(statusText === 'ACCEPTED' || statusText === 'DECLINED' || statusText === 'CANCELED') {
                            alert("Táto objednávka je už akceptovaná alebo odmietnutá a nemožno ju upravovať.");
                            return;
                        }

                        document.getElementById('edit_order_id').value = orderId;
                        document.getElementById('edit_order_time').value = orderTime;
                        document.getElementById('edit_order_type').value = orderType;
                        document.getElementById('edit_notes').value = notes;
                        const editModal = document.getElementById('editModal');
                        editModal.style.top = (e.pageY + 10) + 'px';
                        editModal.style.left = (e.pageX + 10) + 'px';
                        editModal.style.display = 'block';
                    });
                });
            }

            bindEventListeners();

            // AJAX – update objednávky (voláme userUpdate route)
            document.getElementById('editOrderForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const orderId = document.getElementById('edit_order_id').value;
                const formData = new FormData(this);

                fetch(`/orders/user/${orderId}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('editSuccessMessage').style.display = 'block';
                        const row = document.getElementById('orderRow' + orderId);
                        // Aktualizujeme iba čas, typ, stav a poznámky (dátum sa nemení)
                        // Ak server vracia order_time vo formáte H:i:s, môžeme ju skrátiť na H:i
                        const updatedTime = data.order.order_time.substr(0,5);
                        row.children[1].innerText = updatedTime;
                        row.children[2].innerText = data.order.order_type;
                        row.children[3].innerText = data.order.status;
                        row.children[4].innerHTML = data.order.notes
                            ? `<a href="#" class="notes-icon" data-note="${data.order.notes}"><i class="bi bi-file-earmark-text"></i></a>`
                            : '&ndash;';
                        setTimeout(() => {
                            document.getElementById('editSuccessMessage').style.display = 'none';
                            document.getElementById('editModal').style.display = 'none';
                        }, 1500);
                    })
                    .catch(error => {
                        console.error("Error response:", error);
                        document.getElementById('editErrorMessage').style.display = 'block';
                    });
            });

            // AJAX – zrušenie objednávky
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
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(err => { throw err; });
                                }
                                return response.json();
                            })
                            .then(data => {
                                const row = document.getElementById('orderRow' + orderId);
                                row.children[3].innerText = 'CANCELED';
                            })
                            .catch(error => console.error(error));
                    }
                });
            });
        });
    </script>
@endsection
