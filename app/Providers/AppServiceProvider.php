<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SupabaseService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Zaregistrovanie služby do kontajnera
        $this->app->singleton(SupabaseService::class, function ($app) {
            return new SupabaseService();
        });
    }

    public function boot()
    {
        // V tomto prípade nemusíte nič pridávať
    }
}
