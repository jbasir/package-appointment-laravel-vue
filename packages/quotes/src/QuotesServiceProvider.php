<?php

namespace Vendor\Quotes;

use Illuminate\Support\ServiceProvider;
use Vendor\Quotes\Services\QuotesApiClient;

class QuotesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the configuration file
        $this->mergeConfigFrom(
            __DIR__.'/../config/quotes.php', 'quotes'
        );

        // Register the API Client as a singleton
        $this->app->singleton(QuotesApiClient::class, function ($app) {
            return new QuotesApiClient(
                $app['config']['quotes.api_url'],
                $app['config']['quotes.rate_limit'],
                $app['config']['quotes.time_window']
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'quotes');

        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/quotes.php' => config_path('quotes.php'),
        ], 'quotes-config');

        // Publish Vue.js assets
        $this->publishes([
            __DIR__.'/../resources/dist' => public_path('vendor/quotes'),
        ], 'quotes-assets');

        // Publish views for customization
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/quotes'),
        ], 'quotes-views');
    }
} 