<?php

namespace Tequilarapido\TrackIt\Tests;

use Tequilarapido\TrackIt\TrackItServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TrackItServiceProvider::class,
        ];
    }

}