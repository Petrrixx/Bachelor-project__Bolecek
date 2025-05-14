@component('mail::message')
# Vaša rezervácia bola schválená

Dobrý deň, {{ $r->name }},

Vaša rezervácia č. **{{ $r->id }}** na **{{ $r->date->format('d.m.Y') }}** o **{{ \Carbon\Carbon::parse($r->time)->format('H:i') }}** pre **{{ $r->guests }}** hostí bola **schválená**.

Tešíme sa na Vašu návštevu!

@component('mail::button', ['url' => url('/')])
Navštíviť web Gazdovský Dvor
@endcomponent

S pozdravom,  
Tím Gazdovský Dvor
@endcomponent
