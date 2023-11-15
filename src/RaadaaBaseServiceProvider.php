<?php

namespace RaadaaPartners\RaadaaBase;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RaadaaBaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
           $this->bootForConsole();
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/raadaa.php', 'raadaa-base');
        $this->mergeConfigFrom(__DIR__ . '/../config/endpoints.php', 'endpoints');
        $this->mergeConfigFrom(__DIR__.'/../config/filesystems.php', 'filesystems');

        // Register the main class to use with the facade
        $this->app->singleton('raadaa-base', function () {
            return new RaadaaBase;
        });
        //
        $this->app->bind('raadaa-base', function($app) {
            return new RaadaaBase;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['raadaabase'];
    }

    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__ . '/../config/raadaa.php' => config_path('raadaa.php'),
        ], 'raadaa-config');

        $this->publishes([
            __DIR__ . '/../config/endpoints.php' => config_path('endpoints.php'),
        ], 'raadaa-endpoints');

        $this->publishes([
            __DIR__ . '/../config/filesystems.php' => config_path('filesystems.php'),
        ], 'raadaa-filesystems');

        $this->publishes([
            __DIR__ . '/../config/SearchResource.php' => config_path('searchparams.php'),
        ], 'raadaa-search');
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('raadaa.route.prefix'),
            'middleware' => config('raadaa.route.middleware'),
        ];
    }
}
