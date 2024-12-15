<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Dotenv\Dotenv;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        // if (file_exists($path = $this->app->environmentPath().'/'.$this->app->environmentFile())) {
        //     Dotenv::create($this->app->environmentPath(), $this->app->environmentFile())->load();
        // }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Passport::enablePasswordGrant();
        // Passport::routes();
        Passport::enableImplicitGrant();
    }
}
