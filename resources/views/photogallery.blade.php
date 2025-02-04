@extends('layouts.base')

@section('title', "Fotogaléria")

@section('content')
    <style>
        /* Galéria – kontajner */
        .gallery-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 40px;
        }
        /* Jednotlivé položky galérie – fixná veľkosť */
        .gallery-item {
            flex: 0 0 300px; /* Fixná šírka 300px */
            height: 200px;   /* Fixná výška 200px */
            overflow: hidden;
            cursor: pointer;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        /* Modal pre zobrazenie plného obrázka */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 3000;
        }
        .modal-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
            padding: 0;
        }
        .modal-content img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
        }
        .modal-close {
            position: absolute;
            top: 0;
            right: 0;
            margin: 5px;
            font-size: 2.5rem;
            color: #fff;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 3100;
        }
        /* Modal pre nahrávanie obrázka (iba pre adminov) */
        .upload-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 3100;
        }
        .upload-modal-content {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            text-align: left;
            position: relative;
            color: #fff;
        }
        .upload-modal-content h2 {
            margin-top: 0;
        }
        .upload-modal-content input[type="file"] {
            width: 100%;
            margin-top: 10px;
        }
        .upload-modal-content button {
            margin-top: 15px;
        }
        .upload-modal-close {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 2rem;
            color: #fff;
            background: transparent;
            border: none;
            cursor: pointer;
        }
        /* Zoznam vybraných súborov */
        .file-names {
            margin-top: 10px;
            font-size: 0.85rem;
            color: #ffd700;
        }
    </style>

    <div class="content-wrapper text-center p-4">
        <h1 class="text-3xl font-semibold mt-8">Fotogaléria</h1>

        <!-- Tlačidlo pre administrátorov na nahrávanie obrázka -->
        @if(Auth::check() && Auth::user()->isAdmin)
            <div class="my-4">
                <button id="openUploadModal" class="btn btn-primary">Pridať obrázok</button>
            </div>
        @endif

        <div class="gallery-container">
            @foreach($files as $file)
                <div class="gallery-item">
                    <img src="{{ asset('images/fotogaleria/' . $file) }}" alt="Fotografia">
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal pre zobrazenie plného obrázka -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <button class="modal-close">&times;</button>
            <img id="modal-image" src="" alt="Plná fotografia">
        </div>
    </div>

    <!-- Modal pre nahrávanie obrázka (iba pre adminov) -->
    <div id="uploadModal" class="upload-modal">
        <div class="upload-modal-content">
            <button class="upload-modal-close">&times;</button>
            <h2>Nahrať obrázok</h2>
            <p>Vyberte obrázky (.jpg, .jpeg, .png):</p>
            <!-- Drag & Drop oblasť a file input -->
            <div id="dropArea" style="border: 2px dashed #4CAF50; padding: 20px; text-align: center; color: #fff;">
                Presuňte obrázky sem alebo kliknite pre výber.
                <input type="file" id="fileInput" name="images[]" accept=".jpg, .jpeg, .png" multiple style="display: none;">
            </div>
            <!-- Zoznam vybraných súborov -->
            <div id="fileNames" class="file-names"></div>
            <button id="uploadBtn" class="btn btn-primary">Pridať</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            /* Modal pre zobrazenie plného obrázka */
            const modal = document.getElementById('modal');
            const modalImage = document.getElementById('modal-image');
            const closeModalBtn = document.querySelector('.modal-close');

            document.querySelectorAll('.gallery-item img').forEach(img => {
                img.addEventListener('click', function () {
                    modalImage.src = this.src;
                    modal.style.display = 'flex';
                });
            });

            closeModalBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });

            /* Global pole pre akumuláciu súborov */
            let selectedFiles = [];

            /* Modal pre nahrávanie obrázka (iba pre adminov) */
            @if(Auth::check() && Auth::user()->isAdmin)
            const uploadModal = document.getElementById('uploadModal');
            const openUploadModalBtn = document.getElementById('openUploadModal');
            const uploadModalClose = document.querySelector('.upload-modal-close');
            const dropArea = document.getElementById('dropArea');
            const fileInput = document.getElementById('fileInput');
            const fileNamesContainer = document.getElementById('fileNames');
            const uploadBtn = document.getElementById('uploadBtn');

            // Otvoriť modal
            openUploadModalBtn.addEventListener('click', function () {
                uploadModal.style.display = 'flex';
            });

            // Zatvoriť modal kliknutím na X
            uploadModalClose.addEventListener('click', function () {
                uploadModal.style.display = 'none';
                selectedFiles = []; // Vymažeme akumulované súbory
                fileNamesContainer.innerHTML = '';
                fileInput.value = '';
            });

            // Kliknutím na dropArea otvorí file input
            dropArea.addEventListener('click', function () {
                fileInput.click();
            });

            // Ošetriť drag and drop udalosti
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            dropArea.addEventListener('dragover', function () {
                dropArea.style.borderColor = '#ffd700';
            });

            dropArea.addEventListener('dragleave', function () {
                dropArea.style.borderColor = '#4CAF50';
            });

            dropArea.addEventListener('drop', function (e) {
                dropArea.style.borderColor = '#4CAF50';
                if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    addFiles(e.dataTransfer.files);
                }
            });

            fileInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    addFiles(this.files);
                }
            });

            // Funkcia pre pridanie súborov do globálneho poľa
            function addFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    selectedFiles.push(files[i]);
                }
                displayFileNames();
                // Reset file input, aby sa pri ďalšom výbere neprepísali súbory
                fileInput.value = '';
            }

            function displayFileNames() {
                fileNamesContainer.innerHTML = '';
                if (selectedFiles.length > 0) {
                    const list = document.createElement('ul');
                    list.style.listStyleType = 'none';
                    list.style.padding = 0;
                    selectedFiles.forEach(file => {
                        const li = document.createElement('li');
                        li.textContent = file.name;
                        list.appendChild(li);
                    });
                    fileNamesContainer.appendChild(list);
                }
            }

            // Upload tlačidlo – odoslanie všetkých akumulovaných súborov
            uploadBtn.addEventListener('click', function () {
                if (selectedFiles.length === 0) {
                    alert('Prosím, vyberte aspoň jeden súbor.');
                    return;
                }
                // Kontrola typu súborov
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                for (let i = 0; i < selectedFiles.length; i++) {
                    if (!allowedTypes.includes(selectedFiles[i].type)) {
                        alert('Povolené sú iba obrázky vo formátoch JPG, JPEG a PNG.');
                        return;
                    }
                }
                const formData = new FormData();
                selectedFiles.forEach(file => {
                    formData.append('images[]', file);
                });
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("photogallery.upload") }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.ok ? response.json() : Promise.reject('Upload zlyhal'))
                    .then(data => {
                        // Po úspešnom nahratí obnovíme galériu – reload stránky alebo dynamicky aktualizujeme zoznam
                        location.reload();
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Chyba pri nahrávaní obrázka.');
                    });
            });
            @endif
        });
    </script>
@endsection
