<?php

namespace Tequilarapido\TrackIt\Store;

class ArrayStore implements Store
{
    protected $storage = [];

    public function get($key)
    {
        return array_get($this->storage, $key);
    }

    public function set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    public function increment($key, $increment = 1)
    {
        if (!array_key_exists($key, $this->storage)) {
            $this->storage[$key] = 0;
        }
        $this->storage[$key] = $this->storage[$key] + $increment;
    }

    public function exists($key)
    {
        return (bool) array_key_exists($key, $this->storage);
    }

    public function storage()
    {
        return $this->storage;
    }

}