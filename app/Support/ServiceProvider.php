<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var array
     */
    protected array $observers = [];

    /**
     * @return void
     */
    public function boot() : void
    {
        /**
         * @var Model $model
         */
        foreach ($this->getObservers() as $model => $observers) {
            $model::observe($observers);
        }
    }

    /**
     * @return array
     */
    protected function getObservers() : array
    {
        return $this->observers;
    }
}
