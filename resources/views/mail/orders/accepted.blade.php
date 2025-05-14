@component('mail::message')
# Vaša objednávka bola potvrdená

Dobrý deň, {{ $o->user_fullname }},

Vaša objednávka č. **{{ $o->id }}** zo dňa **{{ $o->order_date->format('d.m.Y') }}** bola **prijatá**.

**Typ objednávky:** {{ $o->order_type }}  
@if($o->order_type === 'Osobný odber')
**Čas príchodu:** {{ \Carbon\Carbon::parse($o->order_time)->format('H:i') }}
@else
**Adresa doručenia:** {{ $o->delivery_address }}
@endif

**Poznámky:**  
{{ $o->notes ?? '-' }}

Ďakujeme za Vašu objednávku!

@component('mail::button', ['url' => url('/')])
Navštíviť web Gazdovský Dvor
@endcomponent

S pozdravom,  
Tím Gazdovský Dvor
@endcomponent
