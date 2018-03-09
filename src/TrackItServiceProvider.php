<?php

namespace Tequilarapido\TrackIt;

use Illuminate\Support\ServiceProvider;
use Tequilarapido\TrackIt\Store\Store;

class TrackItServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/trackit.php' => config_path('trackit.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/trackit.php', 'trackit');

        $this->app->bind(Store::class, $this->getConfiguredStoreClass());
    }

    private function getConfiguredStoreClass()
    {
        $driver = ucfirst($this->app['config']['trackit']['store']['driver']);

        if (!class_exists($class = 'Tequilarapido\\TrackIt\\Store\\' . $driver . 'Store')) {
            throw new \Exception("Cannot find track it store implementation for driver [$driver]");
        }

        return $class;
    }
}