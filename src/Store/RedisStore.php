<?php

namespace Tequilarapido\TrackIt\Store;

use Redis;

class RedisStore implements Store
{
    public function get($key)
    {
        return Redis::connection()->get($key);
    }

    public function set($key, $value)
    {
        Redis::connection()->set($key, $value);
    }

    public function increment($key, $increment = 1)
    {
        Redis::connection()->incrby($key, $increment);
    }

    public function exists($key)
    {
        return (bool) Redis::connection()->exists($key);
    }
}