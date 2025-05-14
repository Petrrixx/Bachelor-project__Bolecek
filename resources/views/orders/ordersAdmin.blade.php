{{-- resources/views/orders/ordersAdmin.blade.php --}}
@extends('layouts.base')

@section('title', "Administrácia objednávok")

@section('content')
<div class="container mt-5">
  <h1 class="mb-4 text-white">Administrácia objednávok</h1>

  {{-- Akcie: výber, hromadné vymazanie, filter, reset filter --}}
  <div class="d-flex justify-content-between mb-2">
    <div>
      <button id="toggleSelectBtn" class="btn btn-secondary btn-sm">Vybrať</button>
      <button id="deleteSelectedBtn" class="btn btn-danger btn-sm" style="display:none;">Vymazať vybrané</button>
    </div>
    <div class="d-flex">
      <button id="toggleFilterBtn" class="btn btn-info btn-sm mr-2">Filtrovať</button>
      <button id="resetFilterBtn" class="btn btn-light btn-sm ml-2">Reset filter</button>
    </div>
  </div>

  {{-- Filter okno --}}
  <div id="filterOptions" class="card mb-3 p-3 bg-dark" style="display:none; max-width:800px;">
    <style>
      #filterOptions .form-check-label { color: #fff !important; margin-right: .75rem; }
      #filterOptions .form-check-input { margin-right: .25rem; }
      #filterOptions label.small { color: #fff !important; }
    </style>
    <form id="filterForm" class="form-row align-items-end">
      <div class="form-group col-auto mb-2">
        <label for="date_from" class="small">Dátum od</label>
        <input type="date" id="date_from" name="date_from"
               class="form-control form-control-sm bg-secondary text-white">
      </div>
      <div class="form-group col-auto mb-2 ml-3">
        <label for="date_to" class="small">Dátum do</label>
        <input type="date" id="date_to" name="date_to"
               class="form-control form-control-sm bg-secondary text-white">
      </div>
      <div class="form-group col-auto mb-2 ml-5">
        <label class="small d-block">Status</label>
        @foreach(['PENDING','ACCEPTED','DECLINED','CANCELED'] as $st)
          <div class="form-check form-check-inline">
            <input class="form-check-input filter-status" type="checkbox" value="{{ $st }}" id="status_{{ $st }}" name="status_filter[]">
            <label class="form-check-label small" for="status_{{ $st }}">{{ $st }}</label>
          </div>
        @endforeach
      </div>
      <div class="form-group col-auto mb-2 ml-5">
        <label for="sort_order" class="small">Poradie</label>
        <select id="sort_order" name="sort_order" class="form-control form-control-sm bg-secondary text-white">
          <option value="latest">Najnovšie</option>
          <option value="earliest">Najstaršie</option>
        </select>
      </div>
    </form>
  </div>

  {{-- Tabuľka objednávok --}}
  <table class="table table-hover table-dark text-white">
    <thead>
      <tr>
        <th class="select-col" style="display:none;"><input type="checkbox" id="selectAll"></th>
        <th>ID</th>
        <th>Kontaktné údaje</th>
        <th>Vytvorené<br>(dátum/čas)</th>
        <th>Objednávka<br>(dátum/čas)</th>
        <th>Typ</th>
        <th>Adresa</th>
        <th>Status</th>
        <th>Pozn.</th>
        <th>Akcie</th>
      </tr>
    </thead>
    <tbody id="ordersTableBody">
      @foreach($orders as $order)
        <tr id="orderRow{{ $order->id }}">
          <td class="select-col" style="display:none;">
            <input type="checkbox" class="order-checkbox" data-id="{{ $order->id }}">
          </td>
          <td>{{ $order->id }}</td>
          <td>
            {{ $order->user_fullname }}<br>
            {{ $order->user_contact }}<br>
            {{ $order->user_email }}
          </td>
          <td>
            {{ $order->created_date->format('d.m.Y') }}<br>
            {{ \Carbon\Carbon::parse($order->created_time)->format('H:i') }}
          </td>
          <td>
            {{ $order->order_date->format('d.m.Y') }}<br>
            {{ $order->order_time
                ? \Carbon\Carbon::parse($order->order_time)->format('H:i')
                : '–' }}
          </td>
          <td>{{ $order->order_type }}</td>
          <td>{{ $order->delivery_address ?: '–' }}</td>
          <td>
            @php
              $colors = ['PENDING'=>'secondary','ACCEPTED'=>'success','DECLINED'=>'warning','CANCELED'=>'dark'];
            @endphp
            <span class="badge badge-{{ $colors[$order->status] ?? 'light' }}">
              {{ $order->status }}
            </span>
          </td>
          <td>
            {{ \Illuminate\Support\Str::limit($order->notes, 20, '...') ?: '–' }}
          </td>
          <td>
            @if($order->status==='PENDING')
              <button class="btn btn-sm btn-success accept-btn" data-id="{{ $order->id }}">Prijať</button>
              <button class="btn btn-sm btn-warning decline-btn" data-id="{{ $order->id }}">Odmietnuť</button>
            @endif
            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $order->id }}">Vymazať</button>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <p class="text-center text-muted small mt-3">
    Pre informácie k objednávke volajte na: <strong>032/649 14 01</strong>
  </p>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  let selectMode = false;

  // Prijať / odmietnuť / vymazanie
  document.querySelectorAll('.accept-btn, .decline-btn, .delete-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      let method, url, body;
      if (btn.classList.contains('accept-btn')) {
        method = 'PATCH'; url = `/orders/${id}/update-status`; body = {status:'ACCEPTED'};
      } else if (btn.classList.contains('decline-btn')) {
        method = 'PATCH'; url = `/orders/${id}/update-status`; body = {status:'DECLINED'};
      } else {
        if (!confirm('Naozaj vymazať?')) return;
        method = 'DELETE'; url = `/orders/${id}`;
      }
      fetch(url, {
        method, headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN': csrf
        },
        body: body ? JSON.stringify(body) : null
      })
      .then(r => {
        if (!r.ok) throw new Error;
        return r.json();
      })
      .then(json => {
        if (btn.classList.contains('delete-btn')) {
          document.getElementById('orderRow'+id).remove();
        } else {
          const row = document.getElementById('orderRow'+id);
          const badge = row.children[7].querySelector('span');
          badge.textContent = json.order.status;
          badge.className = 'badge badge-' + ({
            PENDING:'secondary', ACCEPTED:'success',
            DECLINED:'warning', CANCELED:'dark'
          })[json.order.status];
          row.querySelectorAll('.accept-btn,.decline-btn').forEach(b=>b.remove());
        }
      })
      .catch(console.error);
    });
  });

  // Toggle select & deleteMultiple
  document.getElementById('toggleSelectBtn').onclick = () => {
    selectMode = !selectMode;
    document.querySelectorAll('.select-col').forEach(td => {
      td.style.display = selectMode ? 'table-cell' : 'none';
      const cb = td.querySelector('input');
      if (cb) cb.checked = false;
    });
    document.getElementById('deleteSelectedBtn').style.display = 'none';
  };
  document.querySelectorAll('.order-checkbox').forEach(chk => {
    chk.addEventListener('change', () => {
      const any = Array.from(document.querySelectorAll('.order-checkbox')).some(c=>c.checked);
      document.getElementById('deleteSelectedBtn').style.display = any ? 'inline-block' : 'none';
    });
  });
  document.getElementById('deleteSelectedBtn').onclick = () => {
    const ids = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(c=>c.dataset.id);
    if (!ids.length || !confirm('Vymazať vybrané?')) return;
    ids.forEach(id => {
      fetch(`/orders/${id}`, {
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':csrf}
      })
      .then(r=>r.ok && document.getElementById('orderRow'+id).remove())
      .catch(console.error);
    });
  };

  // Filter & reset
  document.getElementById('toggleFilterBtn').onclick = () => {
    const f = document.getElementById('filterOptions');
    f.style.display = f.style.display==='none'?'block':'none';
  };
  document.getElementById('resetFilterBtn').onclick = () => {
    document.getElementById('filterForm').reset();
    document.querySelectorAll('.filter-status').forEach(c=>c.checked=false);
    document.querySelectorAll('#filterForm input,#filterForm select')
      .forEach(i=>i.dispatchEvent(new Event('change')));
  };
  document.querySelectorAll('#filterForm input, #filterForm select').forEach(inp => {
    inp.addEventListener('change', () => {
      const params = new URLSearchParams(new FormData(document.getElementById('filterForm')));
      fetch(`/orders/admin?${params}`, { headers:{Accept:'application/json'} })
      .then(r=>r.json())
      .then(data => {
        const tbody = document.getElementById('ordersTableBody');
        tbody.innerHTML = '';
        data.orders.forEach(o => {
          const badgeColor = {PENDING:'secondary',ACCEPTED:'success',DECLINED:'warning',CANCELED:'dark'}[o.status];
          const tr = document.createElement('tr');
          tr.id = 'orderRow'+o.id;
          tr.innerHTML = `
            <td class="select-col" style="display:${selectMode?'table-cell':'none'}">
              <input type="checkbox" class="order-checkbox" data-id="${o.id}">
            </td>
            <td>${o.id}</td>
            <td>${o.user_fullname}<br>${o.user_contact}<br>${o.user_email}</td>
            <td>${o.created_date}<br>${o.created_time}</td>
            <td>${o.order_date}<br>${o.order_time||'–'}</td>
            <td>${o.order_type}</td>
            <td>${o.delivery_address||'–'}</td>
            <td><span class="badge badge-${badgeColor}">${o.status}</span></td>
            <td>${o.notes? o.notes.substring(0,20)+'...' : '–'}</td>
            <td>
              ${o.status==='PENDING'
                ? `<button class="btn btn-sm btn-success accept-btn" data-id="${o.id}">Prijať</button>
                   <button class="btn btn-sm btn-warning decline-btn" data-id="${o.id}">Odmietnuť</button>`
                : ''}
              <button class="btn btn-sm btn-danger delete-btn" data-id="${o.id}">Vymazať</button>
            </td>`;
          tbody.appendChild(tr);
        });
      })
      .catch(console.error);
    });
  });
});
</script>
@endsection
