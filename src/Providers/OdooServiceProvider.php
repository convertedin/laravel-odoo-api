<?php

namespace Convertedin\LaravelOdooApi\Providers;

use Convertedin\LaravelOdooApi\Odoo;
use Illuminate\Support\ServiceProvider;

class OdooServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/laravel-odoo-api.php', 'laravel-odoo-api');
        
        $this->app->bind(Odoo::class, function () {
            return new Odoo($this->app['config']->get('laravel-odoo-api'));
        });
    }

    private function bootForConsole()
    {
        $this->publishes([
            __DIR__.'/../../config/laravel-odoo-api.php' => config_path('laravel-odoo-api.php'),
        ], 'laravel-odoo-api.config');

    }
}
