@extends('layouts.cutbg')

@section('title', "Denné menu")

@section('content')
    <div class="content-wrapper" style="margin-top: 20px;">
        <div>
            <h1>Vitajte na stránke Gazdovského dvora!</h1>
            <p>Toto je stránka pre Denné menu.</p>
        </div>

        <div class="centered-image" style="margin-top: 70px;">
            <img src="{{ asset('images/menu2110.jpg') }}" alt="Denné menu" class="responsive-image">
        </div>
    </div>
@endsection
