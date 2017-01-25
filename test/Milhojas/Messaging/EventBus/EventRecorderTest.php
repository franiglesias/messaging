<?php

namespace Test\Messaging\EventBus\EventRecorder;

use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\EventBus\EventRecorder;
use PHPUnit\Framework\TestCase;

class EventRecorderTest extends TestCase
{
    private $recorder;

    public function setUp()
    {
        $this->recorder = new EventRecorder();
    }

    public function test_it_stores_and_remove_an_event()
    {
        $event = $this->prophesize(Event::class)->reveal();
        $this->recorder->recordThat($event);

        $this->assertEquals(1, $this->recorder->count());
        $this->assertEquals($event, $this->recorder->shift());
        $this->assertEquals(0, $this->recorder->count());
    }

    public function test_it_can_store_and_count_several_events()
    {
        $event = $this->prophesize(Event::class)->reveal();
        $this->recorder->recordThat($event);
        $this->recorder->recordThat($event);
        $this->recorder->recordThat($event);

        $this->assertEquals(3, $this->recorder->count());
    }

    public function test_it_returns_null_when_trying_to_get_events_from_empty_recorder()
    {
        $result = $this->recorder->shift();
        $this->assertNull($result);
    }

    public function test_it_stores_events_and_can_remove_them_as_stack()
    {
        $event1 = $this->prophesize(Event::class)->reveal();
        $event2 = $this->prophesize(Event::class)->reveal();
        $event3 = $this->prophesize(Event::class)->reveal();

        $this->recorder->recordThat($event1);
        $this->recorder->recordThat($event2);
        $this->recorder->recordThat($event3);

        $this->assertEquals(3, $this->recorder->count());
        $this->assertEquals($event1, $this->recorder->shift());

        $this->assertEquals(2, $this->recorder->count());
        $this->assertEquals($event2, $this->recorder->shift());

        $this->assertEquals(1, $this->recorder->count());
        $this->assertEquals($event3, $this->recorder->shift());
    }

    public function test_it_can_flush_events()
    {
        $event = $this->prophesize(Event::class)->reveal();
        $this->recorder->recordThat($event);
        $this->recorder->recordThat($event);
        $this->recorder->recordThat($event);

        $this->recorder->flush();

        $this->assertEquals(0, $this->recorder->count());
    }

    public function test_it_can_load_events_from_array()
    {
        $event = $this->prophesize(Event::class)->reveal();

        $this->recorder->load([$event, $event, $event]);

        $this->assertEquals(3, $this->recorder->count());
    }
}
