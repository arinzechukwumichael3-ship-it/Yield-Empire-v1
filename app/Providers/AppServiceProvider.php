<?php

namespace App\Providers;

use URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

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
        //
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
        }

        // Override pgsql connection to use custom PostgresConnection for boolean handling
        DB::extend('pgsql', function ($config, $name) {
            $connector = new \Illuminate\Database\Connectors\PostgresConnector();
            $pdo = $connector->connect($config);
            return new \App\Database\PostgresConnection($pdo, $config['database'] ?? '', $config['prefix'] ?? '', $config);
        });
    }
}
