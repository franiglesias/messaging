<?php

namespace spec\Milhojas\Messaging\EventBus;

use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\EventBus\EventBus;
use Milhojas\Messaging\Shared\Pipeline\Pipeline;
use Milhojas\Messaging\Shared\Worker\Worker;
use PhpSpec\ObjectBehavior;

class EventBusSpec extends ObjectBehavior
{
    public function let(Pipeline $pipeline)
    {
        $this->beConstructedWith($pipeline);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(EventBus::class);
    }

    public function it_dispatches_events_through_the_pipeline(Event $event, $pipeline)
    {
        $pipeline->work($event)->shouldBeCalled();
        $this->dispatch($event);
    }

    public function it_accepts_a_unique_worker(Worker $worker, Event $event)
    {
        $this->beConstructedWith($worker);
        $worker->work($event)->shouldBeCalled();
        $this->dispatch($event);
    }
}
