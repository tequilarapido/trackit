<?php

namespace Tequilarapido\TrackIt;
use Tequilarapido\TrackIt\Store\Store;

abstract class Tracker
{
    protected $uid = 'uid';

    protected $prefix = 'prefix';

    /** @var  Store */
    protected $store;

    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    protected function getFullKey($key)
    {
        return "{$this->prefix}:{$this->uid}:{$key}";
    }

    public function get($key)
    {
        return $this->store()->get($this->getFullKey($key));
    }

    public function set($key, $value)
    {
        $this->store()->set($this->getFullKey($key), $value);
    }

    public function jsonGet($key)
    {
        return json_decode($this->get($key), true);
    }

    public function jsonSet($key, $value)
    {
        $this->set($key, json_encode($value));
    }

    public function increment($key, $increment = 1)
    {
        $this->store()->increment($this->getFullKey($key), $increment);
    }

    public function exists($key)
    {
        return $this->store()->exists($key);
    }

    /** @return Store */
    public function store()
    {
        if (!$this->store) {
            $this->store = app()->make(Store::class);
        }

        return $this->store;
    }

    public function __call($method, $parameters)
    {
        // getSomething() -> get(const::SOMETHING)
        if (starts_with($method, 'get')) {
            return $this->dynamicGet($method);
        }

        // setSomething(value)
        if (starts_with($method, 'set')) {
            $this->dynamicSet($method, $parameters[0]);
            return null;
        }

        // incrementSomething(value)
        if (starts_with($method, 'increment')) {
            $this->dynamicIncrement($method, $parameters);
            return null;
        }

        // jsonGetSomething()
        if (starts_with($method, 'jsonGet')) {
            return $this->dynamicJsonGet($method);
        }

        // jsonSetSomething(value)
        if (starts_with($method, 'jsonSet')) {
            $this->dynamicJsonSet($method, $parameters[0]);
            return null;
        }

        throw new UndefinedTrackerConstant("Uknown method called on tracker. [$method]");
    }

    protected function dynamicGet($method)
    {
        $key = strtoupper(str_after($method, 'get'));
        if (!defined("static::{$key}")) {
            throw new UndefinedTrackerConstant("Tracker: Undefined key constant [{$key}]");
        }

        return $this->get(constant("static::{$key}"));
    }

    protected function dynamicSet($method, $value)
    {
        $key = strtoupper(str_after($method, 'set'));
        if (!defined("static::{$key}")) {
            throw new UndefinedTrackerConstant("Tracker: Undefined key constant [{$key}]");
        }

        $this->set(constant("static::{$key}"), $value);
    }

    protected function dynamicJsonGet($method)
    {
        $key = strtoupper(str_after($method, 'jsonGet'));
        if (!defined("static::{$key}")) {
            throw new UndefinedTrackerConstant("Tracker: Undefined key constant [{$key}]");
        }

        return $this->jsonGet(constant("static::{$key}"));
    }

    protected function dynamicJsonSet($method, $value)
    {
        $key = strtoupper(str_after($method, 'jsonSet'));
        if (!defined("static::{$key}")) {
            throw new UndefinedTrackerConstant("Tracker: Undefined key constant [{$key}]");
        }

        $this->jsonSet(constant("static::{$key}"), $value);
    }

    protected function dynamicIncrement($method, $parameters)
    {
        $key = strtoupper(str_after($method, 'increment'));
        if (!defined("static::{$key}")) {
            throw new UndefinedTrackerConstant("Tracker: Undefined key constant [{$key}]");
        }

        if (isset($parameters[0])) {
            $this->increment(constant("static::{$key}"), $parameters[0]);
        } else {
            $this->increment(constant("static::{$key}"));
        }
    }
}