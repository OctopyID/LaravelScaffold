<?php

namespace App\Support\Database;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

abstract class Data implements Arrayable
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @param  Arrayable|array $data
     */
    public function __construct(Arrayable|array $data = [])
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        $this->data = $data;
    }

    /**
     * @param  Arrayable|array $data
     * @return static
     */
    public static function make(Arrayable|array $data = []) : static
    {
        return new static($data); // @phpstan-ignore-line
    }

    /**
     * @param  string $key
     * @param  mixed  $val
     * @return $this
     */
    public function set(string $key, mixed $val) : static
    {
        $this->data[$key] = $val;

        return $this;
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function get(string $key) : mixed
    {
        return $this->data[$key];
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->data;
    }
}
