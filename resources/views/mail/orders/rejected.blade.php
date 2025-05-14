@component('mail::message')
# Objednávka bola odmietnutá

Dobrý deň, {{ $o->user_fullname }},

Vaša objednávka č. **{{ $o->id }}** zo dňa **{{ $o->order_date->format('d.m.Y') }}** bola **odmietnutá**.

Ospravedlňujeme sa za vzniknuté komplikácie. V prípade otázok nás prosím kontaktujte.

@component('mail::button', ['url' => url('/')])
Navštíviť web Gazdovský Dvor
@endcomponent

S pozdravom,  
Tím Gazdovský Dvor
@endcomponent
