<?php

namespace App\System\Providers;

use App\System\Support\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * @return void
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
}
