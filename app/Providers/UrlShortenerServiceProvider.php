<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UrlShortenerInterface;
use App\Services\UrlShortenerService;

class UrlShortenerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(UrlShortenerInterface::class, UrlShortenerService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
