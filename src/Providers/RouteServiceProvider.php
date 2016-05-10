<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @param Request $request
     */
    public function map(Router $router, Request $request)
    {
        $locale = $request->segment(1);

        if (in_array($locale, $this->app->config->get('app.skip_locales'))) {
            $this->skippedLocaleRoutes($router);
            $locale = $this->app->config->get('app.locale');
        } else {
            $this->localeRoutes($router, $locale);
        }
        $this->app->setLocale($locale);
    }

    /**
     * Add a locale prefix to routes
     * @param  \Illuminate\Routing\Router $router $router
     * @param  string $locale
     * @return void
     */
    private function localeRoutes($router, $locale)
    {
        $this->app->setLocale($locale);

        $router->group(['namespace' => $this->namespace, 'prefix' => $locale], function ($router) {
            require app_path('Http/routes.php');
        });
    }

    /**
     * Map routes without locale prefix
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    private function skippedLocaleRoutes($router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
