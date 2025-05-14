@component('mail::message')
# Žiaľ, Vaša rezervácia bola odmietnutá

Dobrý deň, {{ $r->name }},

Rezervácia č. **{{ $r->id }}** na **{{ $r->date->format('d.m.Y') }}** o **{{ \Carbon\Carbon::parse($r->time)->format('H:i') }}** pre **{{ $r->guests }}** hostí bola **odmietnutá**.

Ospravedlňujeme sa za nepríjemnosti. V prípade otázok nás neváhajte kontaktovať.

@component('mail::button', ['url' => url('/')])
Navštíviť web Gazdovský Dvor
@endcomponent

S pozdravom,  
Tím Gazdovský Dvor
@endcomponent
