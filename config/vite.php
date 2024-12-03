<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vite URL
    |--------------------------------------------------------------------------
    |
    | URL, kde sa nachádzajú súbory kompilované pomocou Vite.
    | Pre vývojový režim, bude tento URL ukazovať na server Vite,
    | v produkcii sa nastaví na cestu k výstupným súborom.
    |
    */
    'url' => env('VITE_URL', 'http://localhost:3000'),

    /*
    |--------------------------------------------------------------------------
    | Vite Assets Manifest
    |--------------------------------------------------------------------------
    |
    | Tento súbor bude obsahovať informácie o kompilovaných súboroch,
    | ktoré generuje Vite. Laravel bude používať tento manifest na
    | správne načítanie skriptov a štýlov.
    |
    */
    'manifest' => public_path('build/manifest.json'),

    /*
    |--------------------------------------------------------------------------
    | Vite Proxy (Pre vývojový režim)
    |--------------------------------------------------------------------------
    |
    | Tento parameter špecifikuje, ako bude Laravel komunikovať so serverom Vite.
    | Umožňuje nastaviť proxy, aby sa presmerovali požiadavky na Vite server v režime
    | vývoja.
    |
    */
    'proxy' => env('VITE_PROXY', false),

    /*
    |--------------------------------------------------------------------------
    | Vite Input Files
    |--------------------------------------------------------------------------
    |
    | Toto pole definuje hlavné súbory, ktoré budú vstupné body pre Vite.
    | Laravel bude automaticky generovať odkaz na tieto súbory.
    |
    */
    'input' => [
        'js' => 'resources/js/app.js',
        'css' => 'resources/css/app.css',
    ],

];
