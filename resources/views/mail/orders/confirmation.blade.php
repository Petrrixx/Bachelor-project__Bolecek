@component('mail::message')
# Ďakujeme za objednávku, {{ $o->user_fullname }}!

**Typ objednávky:** {{ $o->order_type }}  
**Dátum:** {{ $o->order_date->format('d.m.Y') }}  
@if($o->order_type === 'Osobný odber')
**Čas príchodu:** {{ \Carbon\Carbon::parse($o->order_time)->format('H:i') }}
@else
**Adresa doručenia:** {{ $o->delivery_address }}
@endif

@if($o->notes)
**Poznámky:**  
{{ $o->notes }}
@endif

@component('mail::button', ['url' => url('/')])
Navštíviť web
@endcomponent
@endcomponent
