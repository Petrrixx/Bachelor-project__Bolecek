@component('mail::message')
# Nová správa z kontaktného formulára

**Od:** {{ $name }}  
**Telefón:** {{ $phone }}  
**E-mail:** {{ $email }}  

---  
**Predmet:** {{ $subject }}

**Správa:**  
{{ $body }}

@endcomponent
