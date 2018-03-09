<?php

namespace Tests;

use Tequilarapido\TrackIt\Store\ArrayStore;
use Tequilarapido\TrackIt\Store\Store;
use Tequilarapido\TrackIt\Tests\TestCase;
use Tequilarapido\TrackIt\Tracker;
use Tequilarapido\TrackIt\UndefinedTrackerConstant;

class TrackerTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        // Set tracker store to array for tests.
        app()->bind(Store::class, ArrayStore::class);

        // Create tracker instance.
        $this->tracker = new class() extends Tracker
        {
            const CURSOR = 'cursor';
        };
        $this->tracker->setPrefix('prefix')->setUid('uid');
    }

    /** @test */
    public function it_sets_values_with_correctly_prefixed_keys()
    {
        $this->tracker->set('something', 100);
                    
        $this->assertTrue($this->tracker->exists('prefix:uid:something'));
    }

    /** @test */
    public function it_sets_and_retreives_correctly_a_value()
    {
        $this->tracker->set('something', 100);

        $this->assertEquals(100, $this->tracker->get('something'));
    }

    /** @test */
    public function it_sets_and_retreives_correctly_a_json_value()
    {
        $this->tracker->jsonSet('something', ['one' => 'value_one', 'two' => 'value_two']);

        $this->assertEquals(['one' => 'value_one', 'two' => 'value_two'], $this->tracker->jsonGet('something'));
    }


    /** @test */
    public function it_increments_corretly_by_one()
    {
        $this->tracker->set('something', 100);

        $this->tracker->increment('something');

        $this->assertEquals(101, $this->tracker->get('something'));
    }

    /** @test */
    public function it_increments_correctly_a_value_by_specified_increment()
    {
        $this->tracker->set('something', 100);

        $this->tracker->increment('something', 10);

        $this->assertEquals(110, $this->tracker->get('something'));
    }

    /** @test */
    public function it_sets_and_get_and_increments_dynamically_a_tracker_constant_key()
    {
        $this->tracker->setCursor(100);
        $this->assertEquals(['prefix:uid:cursor'], array_keys($this->tracker->store()->storage()));
        $this->assertEquals(100, $this->tracker->getCursor());

        $this->tracker->incrementCursor();
        $this->assertEquals(101, $this->tracker->getCursor());

        $this->tracker->incrementCursor(9);
        $this->assertEquals(110, $this->tracker->getCursor());
    }

    /** @test */
    public function it_jsonSet_and_jsonGet_dynamically_a_tracker_constant_key()
    {
        $this->tracker->jsonSetCursor([1, 2]);
        $this->assertEquals([1, 2], $this->tracker->jsonGetCursor());
    }

    /** @test */
    public function it_throws_an_exception_when_using_dynamic_calls_with_a_not_defined_constant()
    {
        $this->expectException(UndefinedTrackerConstant::class);
        $this->tracker->setSomething(100);

        $this->expectException(UndefinedTrackerConstant::class);
        $this->tracker->getSomething();

        $this->expectException(UndefinedTrackerConstant::class);
        $this->tracker->incrementSomething();
    }
}


