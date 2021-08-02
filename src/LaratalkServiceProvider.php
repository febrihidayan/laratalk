<?php

namespace FebriHidayan\Laratalk;

use FebriHidayan\Laratalk\Console\InstallCommand;
use FebriHidayan\Laratalk\Console\PublishCommand;
use FebriHidayan\Laratalk\Models\Group;
use FebriHidayan\Laratalk\Models\Message;
use FebriHidayan\Laratalk\Observers\GroupObserver;
use FebriHidayan\Laratalk\Observers\MessageObserver;
use Illuminate\Support\ServiceProvider;

class LaratalkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerAssetPublishing();
        $this->registerChannels();
        $this->registerMigrations();
        $this->registerObservers();
        $this->registerResources();
        $this->registerRoutes();
        $this->registerTranslations();
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

    /**
     * Register the package channels.
     *
     * @return void
     */
    private function registerChannels()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/channels.php');
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
     * Register the service provider observers
     */
    private function registerObservers()
    {
        Group::observe(GroupObserver::class);
        Message::observe(MessageObserver::class);
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
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
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
}