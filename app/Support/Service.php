<?php

namespace App\Support;

use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class Service
{
    /**
     * @var User|null
     */
    protected User|null $user = null;

    /**
     * @return User
     */
    public function auth() : User
    {
        return Auth::user();
    }

    /**
     * @param  User $user
     * @return $this
     */
    public function forUser(User $user) : static
    {
        $this->user = $user;

        return $this;
    }
}
