@extends('layouts.base')

@section('title', 'Fotogaléria')

@section('content')
<div class="container mx-auto py-8">
  <h1 class="text-3xl mb-6">Fotogaléria</h1>

  @if(session('success'))
    <div class="bg-green-200 text-green-800 p-2 mb-4">
      {{ session('success') }}
    </div>
  @endif

  @if(Auth::check() && Auth::user()->isAdmin)
    <form action="{{ route('photogallery.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="mb-6">
      @csrf

      <div class="flex items-center space-x-4">
        <input
          type="file"
          name="images[]"
          id="fileInput"
          multiple
          accept="image/*"
          class="border p-2 rounded"
        >
        <div id="fileNames" class="text-sm text-gray-700"></div>
      </div>

      @error('images.*')
        <div class="text-red-600 mt-1">{{ $message }}</div>
      @enderror

      <button
        type="submit"
        class="btn btn-primary mt-4"
      >Nahrať obrázky</button>
    </form>
  @endif

  @if($images->isEmpty())
    <p class="text-gray-600">Žiadne obrázky.</p>
  @else
    <div class="gallery-container grid grid-cols-3 gap-4">
      @foreach($images as $img)
        <div class="gallery-item border overflow-hidden relative">
          @if(Auth::check() && Auth::user()->isAdmin)
            <button
              class="absolute top-2 right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center"
              onclick="event.stopPropagation(); deleteImage('{{ $img['public_id'] }}')"
            >&times;</button>
          @endif
          <img
            src="{{ $img['secure_url'] }}"
            alt="{{ $img['public_id'] }}"
            class="w-full h-48 object-cover cursor-pointer"
            onclick="openModal('{{ $img['secure_url'] }}')"
          >
        </div>
      @endforeach
    </div>
  @endif
</div>

<!-- Lightbox modal -->
<div
  id="lightbox"
  class="fixed inset-0 flex bg-black bg-opacity-75 hidden items-center justify-center z-50"
>
  <button
    onclick="closeModal()"
    class="absolute top-4 right-4 text-white text-3xl"
  >&times;</button>
  <img id="lightbox-img" src="" class="max-w-full max-h-full" />
</div>
@endsection

@section('scripts')
<script>
  // 1) Zobrazenie mien vybraných súborov
  const fileInput = document.getElementById('fileInput');
  const fileNames = document.getElementById('fileNames');
  if (fileInput) {
    fileInput.addEventListener('change', () => {
      const files = Array.from(fileInput.files);
      fileNames.textContent = files.map(f => f.name).join(', ');
    });
  }

  // 2) Lightbox
  const lightbox    = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightbox-img');

  function openModal(src) {
    lightboxImg.src = src;
    lightbox.classList.remove('hidden');
  }
  function closeModal() {
    lightbox.classList.add('hidden');
  }
  // click outside img closes
  lightbox.addEventListener('click', e => {
    if (e.target === lightbox) closeModal();
  });

  // 3) Delete funkcia (len pre admin)
  async function deleteImage(publicId) {
  const url = "{{ route('photogallery.destroy', ':id') }}".replace(':id', publicId);
  const res = await fetch(url, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json',
    },
  });
  const data = await res.json();
  if (!res.ok) throw new Error(data.error || 'Chyba pri mazaní');
  location.reload();
}
</script>
@endsection
