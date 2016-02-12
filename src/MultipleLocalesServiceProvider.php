<?php

namespace DidierDeMaeyer\MultipleLocales;

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
        //
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