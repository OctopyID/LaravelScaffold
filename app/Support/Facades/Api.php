<?php

namespace App\Support\Facades;

use Closure;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class Api extends Route
{
    /**
     * @param  Closure $callback
     * @return void
     */
    public static function v1(Closure $callback) : void
    {
        Route::middleware('api')->prefix('api/v1')->as('api.')->group($callback);
    }

    /**
     * @param  string $name
     * @param  string $controller
     * @param  array  $options
     * @return PendingResourceRegistration
     */
    public static function resource(string $name, string $controller, array $options = []) : PendingResourceRegistration
    {
        return parent::apiResource($name, $controller, $options);
    }
}
