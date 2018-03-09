<?php

namespace Tequilarapido\TrackIt\Store;

interface Store
{
    public function get($key);

    public function set($key, $value);

    public function increment($key, $increment = 1);

    public function exists($key);
}