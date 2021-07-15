<?php

namespace Laratalk;

use Illuminate\Support\ServiceProvider;
use Laratalk\Console\InstallCommand;
use Laratalk\Console\PublishCommand;

class LaratalkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->registerTranslations();
        $this->registerMigrations();
        $this->registerAssetPublishing();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laratalk.php',
            'laratalk'
        );

        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
        ]);
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Register the possible views used by Laratalk.
     *
     * @return void
     */
    private function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laratalk');
    }

    /**
     * Register the possible Ttanslations used by Laratalk.
     *
     * @return void
     */
    private function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'laratalk');
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerAssetPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/laratalk'),
            ], 'laratalk-assets');

            $this->publishes([
                __DIR__ . '/../config/laratalk.php' => config_path('laratalk.php'),
            ], 'laratalk-config');
        }
    }
}