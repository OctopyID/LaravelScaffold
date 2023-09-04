<?php

namespace App\Domain\System\Providers;

use App\Domain\System\Support\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register() : void
    {
        Domain::getServiceProviders()->each(function (string $provider) : void {
            $this->app->register($provider);
        });

        if ($this->app->runningInConsole()) {
            Sanctum::ignoreMigrations();

            Factory::guessFactoryNamesUsing(function ($name) {
                return str_replace('Models', 'Database\\Factories', $name) . 'Factory';
            });

            $this->loadMigrationsFrom(Domain::getMigrationsPath());
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        //
    }
}
