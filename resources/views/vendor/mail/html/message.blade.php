<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
<body
    background="https://tzkphgiwxfjssbltutxd.supabase.co/storage/v1/object/public/assets//background.png"
    style="
      margin:0;
      padding:0;
      background-image: https://tzkphgiwxfjssbltutxd.supabase.co/storage/v1/object/public/assets//background.png;
      background-position: center center;
      background-repeat: no-repeat;
      background-size: cover;
    ">
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
