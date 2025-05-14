@extends('layouts.base')

@section('title', "Objednávky")

@section('content')
{{-- Fix pre tmavý text v rozbaľovacom zozname --}}
<style>
  select, select option {
    color: #212529 !important;
    background-color: #ffffff !important;
  }
</style>

<div class="container mt-4">
  <div class="card mx-auto" style="max-width: 720px;">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card-body p-3">
      <!-- Administrátor: tlačidlo na zobrazenie objednávok -->
      <div class="text-right mb-3">
        @auth
          @if(auth()->user()->isAdmin)
            <a href="{{ route('orders.admin.index') }}" class="btn btn-sm btn-primary">
              Zobraziť objednávky
            </a>
          @endif
        @endauth
      </div>

      <h5 class="mb-3">Vytvoriť objednávku</h5>
      <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
        @csrf

        <div class="form-row">
          <!-- Meno a priezvisko -->
          <div class="form-group col-md-6 mb-2">
            <label for="name" class="small mb-1">Meno a priezvisko</label>
            <input type="text" id="name" name="name"
                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback small">{{ $message }}</div>@enderror
          </div>

          <!-- E-mail -->
          <div class="form-group col-md-6 mb-2">
            <label for="email" class="small mb-1">E-mail</label>
            <input type="email" id="email" name="email"
                   class="form-control form-control-sm @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback small">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-row">
          <!-- Telefón -->
          <div class="form-group col-md-6 mb-2">
            <label for="user_contact" class="small mb-1">Telefón</label>
            <input type="tel" id="user_contact" name="user_contact"
                   class="form-control form-control-sm @error('user_contact') is-invalid @enderror"
                   value="{{ old('user_contact') }}" required>
            @error('user_contact')<div class="invalid-feedback small">{{ $message }}</div>@enderror
          </div>

          <!-- Dátum (Pondelok–Piatok) -->
          <div class="form-group col-md-6 mb-2">
            <label for="order_date" class="small mb-1">Dátum (Po–Pia)</label>
            <input type="date" id="order_date" name="order_date"
                   class="form-control form-control-sm @error('order_date') is-invalid @enderror"
                   value="{{ old('order_date') }}" required>
            @error('order_date')<div class="invalid-feedback small">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="form-row">
          <!-- Typ objednávky -->
          <div class="form-group col-md-6 mb-2">
            <label for="order_type" class="small mb-1">Typ objednávky</label>
            <select id="order_type" name="order_type"
                    class="form-control form-control-sm @error('order_type') is-invalid @enderror"
                    required>
              <option value="" style="color:#212529;">– vyberte –</option>
              <option value="Osobný odber" {{ old('order_type')=='Osobný odber'?'selected':'' }} style="color:#212529;">
                Osobný odber
              </option>
              <option value="Rozvoz" {{ old('order_type')=='Rozvoz'?'selected':'' }} style="color:#212529;">
                Rozvoz
              </option>
            </select>
            @error('order_type')<div class="invalid-feedback small">{{ $message }}</div>@enderror
          </div>

          <!-- Čas príchodu / Adresa doručenia (dynamicky) -->
          <div class="form-group col-md-6 mb-2" id="pickup_time_group">
            <label for="pickup_time" class="small mb-1">Čas príchodu</label>
            <input type="time" id="pickup_time" name="pickup_time"
                   class="form-control form-control-sm @error('pickup_time') is-invalid @enderror"
                   min="10:00" max="15:00" value="{{ old('pickup_time') }}">
            @error('pickup_time')<div class="invalid-feedback small">{{ $message }}</div>@enderror
          </div>
          <div class="form-group col-md-6 mb-2" id="delivery_address_group">
            <label for="delivery_address" class="small mb-1">Adresa doručenia</label>
            <input type="text" id="delivery_address" name="delivery_address"
                   class="form-control form-control-sm @error('delivery_address') is-invalid @enderror"
                   value="{{ old('delivery_address') }}">
            @error('delivery_address')<div class="invalid-feedback small">{{ $message }}</div>@enderror
          </div>
        </div>

        <!-- Poznámky -->
        <div class="form-group mb-3">
          <label for="notes" class="small mb-1">Poznámky</label>
          <textarea id="notes" name="notes"
                    class="form-control form-control-sm @error('notes') is-invalid @enderror"
                    rows="2"
                    placeholder="P1 - 1x, M4 - 4x, Pizza Romana - 1x ...">{{ old('notes') }}</textarea>
          @error('notes')<div class="invalid-feedback small">{{ $message }}</div>@enderror
        </div>

        <input type="hidden" name="status" value="PENDING">
        <button type="submit" class="btn btn-sm btn-primary btn-block">
          Poslať objednávku
        </button>

        <p class="text-center text-muted small mt-3">
          V prípade otázok k objednávke volajte na číslo: <strong>032/649 14 01</strong>
        </p>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const dateInput      = document.getElementById('order_date');
  const typeSelect     = document.getElementById('order_type');
  const pickupGroup    = document.getElementById('pickup_time_group');
  const deliveryGroup  = document.getElementById('delivery_address_group');

  // Iba pracovné dni (Po–Pia)
  dateInput.addEventListener('change', () => {
    const [year, month, day] = dateInput.value.split('-');
    const dt = new Date(year, month - 1, day);   // lokálny midnight
    const weekday = dt.getDay();                 // 0=Ne,1=Po,...,6=So

    if (weekday === 0 || weekday === 6) {
      alert('Vyberte prosím deň od pondelka do piatka.');
      dateInput.value = '';
    }
  });

  // Prepínanie viditeľnosti polí podľa typu objednávky
  function updateType() {
    if (typeSelect.value === 'Osobný odber') {
      pickupGroup.style.display = '';
      deliveryGroup.style.display = 'none';
    } else if (typeSelect.value === 'Rozvoz') {
      deliveryGroup.style.display = '';
      pickupGroup.style.display = 'none';
    } else {
      pickupGroup.style.display = 'none';
      deliveryGroup.style.display = 'none';
    }
  }

  typeSelect.addEventListener('change', updateType);
  updateType();
});
</script>
@endsection
