<x-mail::message>

{{-- hlavný obsah --}}
# Ďakujeme za rezerváciu, {{ $r->name }}!

Prijali sme Vašu žiadosť:

- **Dátum:** {{ $r->date->format('d.m.Y') }}
- **Čas:** {{ $r->time }}
- **Počet hostí:** {{ $r->guests }}

O schválení či úprave Vás budeme informovať e-mailom alebo telefonicky.

{{-- tlačidlo --}}
<x-mail::button :url="url('/')">
Navštíviť web
</x-mail::button>

</x-mail::message>
