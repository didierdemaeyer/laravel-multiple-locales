<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class Language
{
    public function __construct(Application $app, Redirector $redirector, Request $request)
    {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Make sure the current local exists
        $locale = $request->segment(1);

        // If the locale is added to to skip_locales array continue without locale
        if (in_array($locale, $this->app->config->get('app.skip_locales'))) {
            return $next($request);
        }

        // If the locale does not exist in the locales array continue with the fallback_locale
        if ( ! array_key_exists($locale, $this->app->config->get('app.locales'))) {
            $segments = $request->segments();
            $segments[0] = $this->app->config->get('app.fallback_locale');

            return $this->redirector->to(implode('/', $segments));
        }

        // Set the locale
        $this->app->setLocale($locale);

        return $next($request);
    }
}