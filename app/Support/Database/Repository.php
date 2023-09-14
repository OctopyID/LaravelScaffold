<?php

namespace App\Support\Database;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read Model $model
 */
abstract class Repository
{
    /**
     * @var User|null
     */
    protected ?User $user = null;

    /**
     * @param  User $user
     * @return $this
     */
    public function forUser(User $user) : static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param  array|Data $data
     * @return Model
     */
    public function create(Data|array $data) : Model
    {
        if ($data instanceof Data) {
            $data = $data->toArray();
        }

        return $this->model->create($data);
    }

    /**
     * @return Builder
     */
    protected function newQuery() : Builder
    {
        return $this->model->newQuery();
    }
}
