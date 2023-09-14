<?php

namespace App\System\Providers;

use App\System\Support\Domain;
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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        //
    }
}
