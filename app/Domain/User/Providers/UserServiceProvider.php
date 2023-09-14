<?php

namespace App\Domain\User\Providers;

use App\Domain\User\Models\AccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class UserServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot() : void
    {
        Sanctum::usePersonalAccessTokenModel(
            AccessToken::class,
        );
    }
}
