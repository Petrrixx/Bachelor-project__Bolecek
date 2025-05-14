@component('mail::message')

# Nová rezervácia #{{ $r->id }}

> Na webe práve prišla nová žiadosť o rezerváciu.  
> Všetky detaily nájdeš nižšie a v administrácii si môžeš požiadavku schváliť alebo odmietnuť.

---

**Meno:** {{ $r->name }}  
**E-mail:** {{ $r->email }}  
**Telefón:** {{ $r->user_contact }}  

**Dátum:** {{ $r->date->format('d.m.Y') }}  
**Čas:** {{ $r->time }}  
**Počet hostí:** {{ $r->guests }}

@component('mail::button', ['url' => route('reservation.admin')])
Otvoriť v administrácii
@endcomponent

@endcomponent

