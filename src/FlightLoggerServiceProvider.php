<?php

declare(strict_types=1);

namespace Tschope\FlightLogger;

use Illuminate\Support\ServiceProvider;

/**
 * FlightLogger Service Provider
 *
 * Laravel service provider for FlightLogger integration
 */
class FlightLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/flightlogger.php',
            'flightlogger'
        );

        // Register the connector as a singleton
        $this->app->singleton(FlightLoggerConnector::class, function ($app) {
            return new FlightLoggerConnector();
        });
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/flightlogger.php' => config_path('flightlogger.php'),
            ], 'flightlogger-config');
        }
    }
}
