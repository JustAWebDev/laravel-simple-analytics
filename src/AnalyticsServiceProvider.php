<?php

namespace JustAWebDev\Analytics;

use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/analytics.php',
            'analytics'
        );

        $this->app->singleton('analytics', function () {
            return new Analytics();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/analytics.php' => config_path('analytics.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\ReportCommand::class,
            ]);
        }
    }
}