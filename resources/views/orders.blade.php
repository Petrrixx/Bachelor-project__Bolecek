@extends('layouts.cutbg')

@section('title', "Objednávky")

@section('content')
    <div class="container">
        <h1>Objednávky</h1>
        <div class="order-box" style="margin-top: 20px">
            <h2>Vytvoriť objednávku</h2>
            <form action="#" method="POST">
                <label for="foodItem">Vyberte jedlo:</label>
                <input type="text" id="foodItem" name="foodItem" placeholder="Zadajte názov jedla">

                <label for="quantity">Množstvo:</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1">

                <button type="submit">Objednať</button>
            </form>
        </div>
    </div>
@endsection
