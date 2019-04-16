<?php

namespace PortedCheese\SeoIntegration;

use Illuminate\Support\ServiceProvider;

class SeoIntegrationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Подгрузка миграций.
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        // Подгрузка шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'seo-integration');
        // Подгрузка роутов.
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
        // Конфигурация.
        $this->publishes([
            __DIR__ . '/config/seo-integration.php' => config_path('seo-integration.php'),
        ], 'config');
    }

    public function register()
    {

    }

}
