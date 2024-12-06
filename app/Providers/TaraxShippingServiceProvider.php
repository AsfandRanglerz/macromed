<?php

namespace App\Providers;

use App\Services\TaraxShippingService;
use Illuminate\Support\ServiceProvider;

class TaraxShippingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TaraxShippingService::class, function () {
            return new TaraxShippingService();
        });
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
