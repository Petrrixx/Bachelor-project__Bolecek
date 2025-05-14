@component('mail::message')
# Nová objednávka #{{ $o->id }}

**Typ objednávky:** {{ $o->order_type }}

**Meno:** {{ $o->user_fullname }}  
**E-mail:** {{ $o->user_email }}  
**Telefón:** {{ $o->user_contact }}

**Dátum:** {{ $o->order_date->format('d.m.Y') }}  
**Čas príchodu:** {{ $o->order_time ? \Carbon\Carbon::parse($o->order_time)->format('H:i') : '–' }}  
**Adresa doručenia:** {{ $o->delivery_address ?: '–' }}

@if($o->notes)
**Poznámky:**  
{{ $o->notes }}
@endif

@component('mail::button', ['url' => route('orders.admin.index')])
Otvoriť administráciu
@endcomponent

@endcomponent
