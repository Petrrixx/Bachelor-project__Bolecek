@extends('layouts.base')

@section('title', "Fotogaléria")

@section('content')
    <div class="photo-gallery">
        <h1>Fotogaléria</h1>
        <div class="gallery-container">
            <div class="gallery-item">
                <img src="{{ asset('images/photo1.jpg') }}" alt="Fotografia 1">
            </div>
            <div class="gallery-item">
                <img src="{{ asset('images/photo2.jpg') }}" alt="Fotografia 2">
            </div>
            <div class="gallery-item">
                <img src="{{ asset('images/photo3.jpg') }}" alt="Fotografia 3">
            </div>
            <div class="gallery-item">
                <img src="{{ asset('images/photo4.jpg') }}" alt="Fotografia 4">
            </div>
            <div class="gallery-item">
                <img src="{{ asset('images/photo5.jpg') }}" alt="Fotografia 5">
            </div>
            <div class="gallery-item">
                <img src="{{ asset('images/photo6.jpg') }}" alt="Fotografia 6">
            </div>
            <!-- Ešte neviem ako toto ZAUTOMATIZUJEM, nejaká funckia by sa hodila pre pridávanie itemov -->
        </div>
    </div>
@endsection
