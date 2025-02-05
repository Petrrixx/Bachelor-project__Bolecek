@extends('layouts.base')

@section('title', "Admin Mailbox")

@section('content')
    <!-- INLINE štýly používam kvôli nezhodám v style.css -->
    <style>
        /* Základné štýly pre modal */
        .modal-content {
            background-color: #2a2a2a;
            color: #fff;
            border: 1px solid #4CAF50;
            border-radius: 8px;
            margin: 0 auto; /* Centrovanie modalu */
        }

        .modal-header {
            border-bottom: 1px solid #4CAF50;
        }

        .modal-footer {
            border-top: 1px solid #4CAF50;
        }

        .btn-close {
            filter: invert(1);
        }

        /* Maximalizovaný modal */
        .modal-maximized {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Blur efekt pre pozadie */
        .modal-backdrop-blur {
            backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Skrytie modalu na začiatku */
        #messageModal {
            display: none;
        }

        #messageModal.show {
            display: block;
        }

        /* Zrušenie pridávania padding-right na body */
        body.modal-open {
            padding-right: 0 !important;
            overflow: auto !important;
        }

        /* Centrovanie modalu */
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        .modal-content {
            margin: 0 auto;
        }
    </style>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Mailbox</h1>

        <!-- Hlavná akčná lišta s tlačidlom pre vymazanie všetkých správ -->
        <div class="d-flex justify-content-end mb-3">
            <button id="deleteAllBtn" class="btn btn-danger">
                <i class="bi bi-trash"></i> Vymazať všetky správy
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="messagesTable">
                <thead>
                <tr>
                    <th>Meno</th>
                    <th>Email</th>
                    <th>Predmet</th>
                    <th>Akcie</th>
                </tr>
                </thead>
                <tbody>
                @foreach($messages as $message)
                    <tr data-id="{{ $message->id }}">
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email }}</td>
                        <td>{{ $message->subject }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm view-message" data-id="{{ $message->id }}" data-bs-toggle="modal" data-bs-target="#messageModal">
                                <i class="bi bi-eye"></i> Zobraziť
                            </button>
                            <!-- Individuálna ikona pre vymazanie správy -->
                            <button class="btn btn-danger btn-sm delete-message" data-id="{{ $message->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pridajte tento div pre vlastný backdrop -->
    <div id="customBackdrop" class="custom-backdrop"></div>

    <!-- Modal pre zobrazenie správy -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Správa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="messageContent">
                    <!-- Obsah správy sa načíta sem pomocou AJAXu -->
                    <p><strong>Meno:</strong> <span id="messageName"></span></p>
                    <p><strong>Telefón:</strong> <span id="messagePhone"></span></p>
                    <p><strong>Email:</strong> <span id="messageEmail"></span></p>
                    <p><strong>Predmet:</strong> <span id="messageSubject"></span></p>
                    <p><strong>Správa:</strong> <span id="messageText"></span></p>
                    <div id="messageAttachments">
                        <strong>Prílohy:</strong>
                        <ul id="attachmentsList"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvoriť</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Predpokladám, že v layoute máš meta tag s CSRF tokenom:
        // <meta name="csrf-token" content="{{ csrf_token() }}">
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // AJAX pre načítanie obsahu správy
        document.querySelectorAll('.view-message').forEach(button => {
            button.addEventListener('click', function() {
                const messageId = this.getAttribute('data-id');
                fetch(`/contact/messages/${messageId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Naplnenie modalu dátami
                        document.getElementById('messageName').textContent = data.name;
                        document.getElementById('messagePhone').textContent = data.phone;
                        document.getElementById('messageEmail').textContent = data.email;
                        document.getElementById('messageSubject').textContent = data.subject;
                        document.getElementById('messageText').textContent = data.message;

                        // Naplnenie príloh
                        const attachmentsList = document.getElementById('attachmentsList');
                        attachmentsList.innerHTML = ''; // Vyčistenie zoznamu
                        if (data.attachments && data.attachments.length > 0) {
                            data.attachments.forEach(attachment => {
                                const listItem = document.createElement('li');
                                const link = document.createElement('a');
                                link.href = attachment.url;
                                link.textContent = attachment.name;
                                link.target = '_blank';
                                listItem.appendChild(link);
                                attachmentsList.appendChild(listItem);
                            });
                        } else {
                            attachmentsList.innerHTML = '<li>Žiadne prílohy</li>';
                        }

                        // Zobrazenie modalu
                        const modal = new bootstrap.Modal(document.getElementById('messageModal'));
                        modal.show();
                    })
                    .catch(error => console.error(error));
            });
        });

        // Individuálne vymazanie správy
        document.querySelectorAll('.delete-message').forEach(button => {
            button.addEventListener('click', function() {
                const messageId = this.getAttribute('data-id');
                if (confirm('Chcete vymazať správu?')) {
                    fetch(`/contact/messages/${messageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                // Odstránenie riadku z tabuľky
                                const row = document.querySelector(`tr[data-id="${messageId}"]`);
                                row.parentNode.removeChild(row);
                            } else {
                                throw new Error('Chyba pri vymazávaní správy.');
                            }
                        })
                        .catch(error => console.error(error));
                }
            });
        });

        // Vymazanie všetkých správ
        // Vymazanie všetkých správ
        document.getElementById('deleteAllBtn').addEventListener('click', function() {
            if (confirm('Naozaj chcete vymazať všetky správy?')) {
                fetch(`/contact/delete-all`, {  // URL upravená na /contact/delete-all
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (response.ok) {
                            // Vyprázdnime obsah tabuľky
                            document.querySelector('#messagesTable tbody').innerHTML = '';
                        } else {
                            throw new Error('Chyba pri vymazávaní všetkých správ.');
                        }
                    })
                    .catch(error => console.error(error));
            }
        });

    </script>
@endsection
