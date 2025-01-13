<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCompositePrimaryKeyTrait
{
    public function getIncrementing()
    {
        return false;
    }

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getAttribute($keyName));
        }

        return $query;
    }

    public static function find($ids, $columns = ['*'])
    {
        $me = new static;
        $query = $me->newQuery();

        foreach ($me->getKeyName() as $key) {
            $query->where($key, '=', $ids[$key]);
        }

        return $query->first($columns);
    }

    public function getKeyName()
    {
        return $this->primaryKey;
    }
}