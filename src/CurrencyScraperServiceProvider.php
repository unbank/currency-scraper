<?php

namespace Unbank\CurrencyScraper;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CurrencyScraperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Unbank\CurrencyScraper\Commands\FetchCurrency::class
            ]);
        }

    }
}
