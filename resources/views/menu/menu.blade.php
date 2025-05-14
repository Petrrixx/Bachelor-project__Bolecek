@extends('layouts.base')

@section('title', "Jedálny lístok")

@section('content')
    <div class="content-wrapper text-center p-4">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-semibold mt-8">Vítame Vás a prajeme Vám dobrú chuť!</h1>
        <p class="text-lg sm:text-xl md:text-2xl mt-4">Jedálny lístok</p>

        <!-- Úvodný text s informáciami a zoznam alergénov -->
        <div class="menu-info-container">
            <div class="menu-info">
                <p>
                    Ceny jedál sú zmluvné a sú tvorené na základe vlastných kalkulácií.<br>
                    Polovičná porcia – ak je možné ju pripraviť, je účtovaná 80 % z ceny celej porcie.<br>
                    Cena za jedno balenie jedla je 0,30 €, cena za papierovú tašku je 0,10 €.<br>
                    Doba prípravy jedál na objednávku je cca 30 – 45 minút, v závislosti od obsadenosti reštaurácie.<br>
                    Za pochopenie ďakujeme.
                </p>
                <br>
                <p>
                    Alergény sú uvedené číslicou pri jednotlivých jedlách.<br>

                    1. Obilniny obsahujúce lepok (pšenica, raž, jačmeň, ovoc, špalda, kamut alebo ich hybridné odrody)<br>
                    2. Kôrovce a výrobky z nich<br>
                    3. Vajcia a výrobky z nich<br>
                    4. Ryby a výrobky z nich<br>
                    5. Arašidy a výrobky z nich<br>
                    6. Sójové zrná a výrobky z nich<br>
                    7. Mlieko a výrobky z neho<br>
                    8. Orechy (mandle, lieskové, vlašské, kešu, pekanové, para orechy, pistácie, makadamové a výrobky z nich)<br>
                    9. Zeler a výrobky z neho<br>
                    10. Horčica a výrobky z nej<br>
                    11. Sezamové semená a výrobky z nich<br>
                    12. Oxid síričitý a siričitany v koncentráciách vyšších ako 10 mg/kg alebo 10 mg/l<br>
                    13. Vlčí bôb a výrobky z neho<br>
                    14. Mäkkýše a výrobky z nich
                </p>
            </div>

            <!-- Pravý stĺpec s obrázkami -->
            <div class="menu-images">
                <img src="{{ asset('images/cernohor.jpg') }}" alt="Obrázok 1" class="w-full">
                <img src="{{ asset('images/bryndzove.jpg') }}" alt="Obrázok 2" class="w-full">
                <img src="{{ asset('images/fitnes.png') }}" alt="Obrázok 3" class="w-full">
            </div>
        </div>

        <!-- Tabuľka s jedlami so skupinami podľa kategórie -->
        <div class="menu-table my-4 mx-auto" style="max-width: 800px;">
            <table class="table w-full text-left border-collapse">
                <thead>
                <tr>
                    <th class="border">Jedlo</th>
                    <th class="border">Hmotnosť</th>
                    <th class="border">Cena</th>
                    <th class="border">Alergény</th>
                </tr>
                </thead>
                <tbody>
                @foreach($groupedItems as $category => $items)
                    <!-- Nadpis kategórie -->
                    <tr>
                        <td colspan="4" class="category-header border">
                            {{ mb_strtoupper($category, 'UTF-8') }}
                        </td>
                    </tr>
                    @foreach($items as $item)
                        <tr>
                            <td class="border">{{ $item->food_name }}</td>
                            <td class="border">{{ $item->hmotnost }}</td>
                            <td class="border">{{ $item->cena }}</td>
                            <td class="border">{{ $item->all_allergens }}</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Ak som admin, zobrazí administratívny pohľad -->
        @if(Auth::check() && Auth::user()->isAdmin)
            <div class="admin-view mt-8">
                <a href="{{ route('menu.admin') }}" class="btn btn-warning">Administratívny pohľad</a>
            </div>
        @endif
    </div>
@endsection
