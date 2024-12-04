<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Middleware pre dôveryhodné proxy
        \App\Http\Middleware\TrustProxies::class,

        // Middleware pre CORS (Cross-Origin Resource Sharing)
        \Fruitcake\Cors\HandleCors::class,

        // Middleware pre údržbu aplikácie
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Middleware na validáciu veľkosti POST požiadaviek
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Middleware na orezávanie reťazcov a konvertovanie prázdnych reťazcov na null
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // Middleware na šifrovanie cookies
            \App\Http\Middleware\EncryptCookies::class,

            // Middleware na pridanie queued cookies do odpovede
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

            // Middleware na začatie session
            \Illuminate\Session\Middleware\StartSession::class,

            // Middleware na zdieľanie chýb z session do view
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            // Middleware na ochranu pred CSRF útokmi
            \App\Http\Middleware\VerifyCsrfToken::class,

            // Middleware na substitúciu route bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Middleware na throttle požiadaviek (obmedzenie počtu požiadaviek)
            'throttle:api',

            // Middleware na substitúciu route bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // Middleware na autentifikáciu
        'auth' => \App\Http\Middleware\Authenticate::class,

        // Middleware na autentifikáciu pomocou základného HTTP autentifikačného schématu
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,

        // Middleware na presmerovanie ak je užívateľ autentifikovaný
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        // Middleware na throttle požiadaviek
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        // Middleware na zabezpečenie, že email je overený
        'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,

        // Vlastné middleware
        'auth.supabase' => \App\Http\Middleware\CheckIfAuthenticated::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'isAdmin' => \App\Http\Middleware\IsAdmin::class,
    ];
}
