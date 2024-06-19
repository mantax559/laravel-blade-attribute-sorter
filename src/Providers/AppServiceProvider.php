<?php

namespace Mantax559\LaravelBladeAttributeSorter\Providers;

use Illuminate\Support\ServiceProvider;
use Mantax559\LaravelBladeAttributeSorter\Commands\SortBladeAttributesCommand;

class AppServiceProvider extends ServiceProvider
{
    private const PATH_CONFIG = __DIR__.'/../../config/laravel-blade-attribute-sorter.php';

    public function boot(): void
    {
        $this->publishes([
            self::PATH_CONFIG => config_path('laravel-blade-attribute-sorter.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SortBladeAttributesCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(self::PATH_CONFIG, 'laravel-blade-attribute-sorter');
    }
}
