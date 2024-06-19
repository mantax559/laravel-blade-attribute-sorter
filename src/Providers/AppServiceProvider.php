<?php

namespace Mantax559\LaravelBladeAttributeSorter\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    private const PATH_CONFIG = __DIR__.'/../../config/laravel-blade-attribute-sorter.php';

    public function boot(): void
    {
        $this->publishes([
            self::PATH_CONFIG => config_path('laravel-blade-attribute-sorter.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(self::PATH_CONFIG, 'laravel-blade-attribute-sorter');
    }
}
