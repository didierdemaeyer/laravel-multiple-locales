<?php

namespace DidierDeMaeyer\MultipleLocales\Providers;

use Illuminate\Support\ServiceProvider;

class MultipleLocalesServiceProvider extends ServiceProvider
{
    protected $commands = [
        'DidierDeMaeyer\MultipleLocales\Commands\InstallMultipleLocalesCommand',
        'DidierDeMaeyer\MultipleLocales\Commands\RemoveMultipleLocalesCommand',
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/' => config_path(),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../../config/multiple-locales.php', 'multiple-locales');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}