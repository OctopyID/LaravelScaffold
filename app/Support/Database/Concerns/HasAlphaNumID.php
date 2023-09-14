<?php

namespace App\Support\Database\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait HasAlphaNumID
{
    /**
     * Initialize the trait.
     *
     * @return void
     */
    public function initializeHasAlphaNumID() : void
    {
        $this->usesUniqueIds = true;
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds() : array
    {
        return [$this->getKeyName()];
    }

    /**
     * Generate a new Code for the model.
     *
     * @return string
     */
    public function newUniqueId() : string
    {
        return strtoupper(Str::random(8));
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  Model|Relation $query
     * @param  mixed          $value
     * @param  string|null    $field
     * @return Relation
     *
     * @throws ModelNotFoundException
     */
    public function resolveRouteBindingQuery($query, $value, $field = null) : Relation
    {
        if ($field && in_array($field, $this->uniqueIds()) && ! Str::isUlid($value)) {
            throw (new ModelNotFoundException)->setModel(get_class($this), $value);
        }

        if (! $field && in_array($this->getRouteKeyName(), $this->uniqueIds()) && ! Str::isUlid($value)) {
            throw (new ModelNotFoundException)->setModel(get_class($this), $value);
        }

        return parent::resolveRouteBindingQuery($query, $value, $field);
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType() : string
    {
        if (in_array($this->getKeyName(), $this->uniqueIds())) {
            return 'string';
        }

        return $this->keyType;
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing() : bool
    {
        if (in_array($this->getKeyName(), $this->uniqueIds())) {
            return false;
        }

        return $this->incrementing;
    }
}
