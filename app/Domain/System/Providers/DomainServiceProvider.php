<?php

namespace App\Domain\System\Providers;

use App\Domain\System\Support\Domain;
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
        // register all service providers inside domain directory.
        Domain::discoverServiceProviders()->each(function ($provider) {
            $this->app->register($provider);
        });

        // register all migrations inside domain directory.
        Domain::discoverMigrationDirectories()->each(function ($directory) {
            $this->loadMigrationsFrom($directory);
        });

        if ($this->app->runningInConsole()) {
            Sanctum::ignoreMigrations();

            Factory::guessFactoryNamesUsing(function ($name) {
                return str_replace('Models', 'Database\\Factories', $name) . 'Factory';
            });
        }
    }
}
