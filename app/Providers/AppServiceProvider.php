<?php

namespace App\Providers;

use URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

ini_set('memory_limit','-1');

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the cache-busting URL generator before the framework resolves
        // 'url'. Re-binding 'url' here (instead of boot()) is critical: boot()
        // would fire rebound callbacks on an already-resolved singleton, which
        // re-resolves 'url' through the container and recurses infinitely.
        $this->app->singleton('url', function ($app) {
            $routes = $app['router']->getRoutes();
            $app->instance('routes', $routes);
            return new \App\Routing\VersionedUrlGenerator(
                $routes,
                $app->rebinding('request', function ($app, $request) {
                    $app['url']->setRequest($request);
                }),
                $app['config']['app.asset_url']
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);
        if($this->app->environment('production')) 
        {
            \URL::forceScheme('https');
            \URL::forceRootUrl(config('app.url'));
        }

        // Cache-bust static assets (append ?v=<mtime>) so edits reflect immediately.
        // Cloudflare caches static files for its default 4h Edge TTL when the origin
        // sends no Cache-Control, which made frontend asset edits appear stale.
        // The custom UrlGenerator is registered in register() (see above).
    }
}
