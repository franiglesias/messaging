<?php

namespace Test\Messaging\Shared\Worker;

use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\EventBus\EventBus;
use Milhojas\Messaging\EventBus\EventRecorder;
use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Worker\DispatchEventsWorker;
use PHPUnit\Framework\TestCase;

class DispatchEventsWorkerTest extends TestCase
{
    private $recorder;
    private $worker;
    private $bus;

    public function setUp()
    {
        $this->bus = $this->prophesize(EventBus::class);
        $this->recorder = $this->prophesize(EventRecorder::class);
        $this->worker = new DispatchEventsWorker($this->bus->reveal(), $this->recorder->reveal());
    }

    public function test_it_executes_sending_passing_recorded_events_to_event_bus()
    {
        $event = $this->prophesize(Event::class);
        $message = $this->prophesize(Message::class);
        $this->recorder->shift()->shouldBeCalled(4)->willReturn($event->reveal(), $event->reveal(), $event->reveal(), null);
        $this->bus->dispatch($event->reveal())->shouldBeCalled(3);
        $this->worker->work($message->reveal());
    }
}
