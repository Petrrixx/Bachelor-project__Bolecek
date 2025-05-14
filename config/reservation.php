<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kapacita reštaurácie
    |--------------------------------------------------------------------------
    | Maximálny počet hostí, ktorí môžu byť súčasne usadení
    | v tom istom časovom okne (viď time_window).
    */
    'capacity'    => env('RESERVATION_CAPACITY', 60),

    /*
    |--------------------------------------------------------------------------
    | Dĺžka časového okna (v minútach)
    |--------------------------------------------------------------------------
    | Rezervácie, ktoré majú čas začiatku v rozsahu
    |   [requested‑time − time_window, requested‑time + time_window]
    | sa budú počítať do jedného „slotu“.
    */
    'time_window' => env('RESERVATION_WINDOW', 90),
];
