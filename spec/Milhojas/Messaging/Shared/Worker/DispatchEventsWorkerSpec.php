<?php

namespace spec\Milhojas\Messaging\Shared\Worker;

use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Worker\DispatchEventsWorker;
use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\EventBus\EventBus;
use Milhojas\Messaging\EventBus\EventRecorder;
use Milhojas\Messaging\Shared\Worker\Worker;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DispatchEventsWorkerSpec extends ObjectBehavior
{
    public function let(EventBus $eventBus, EventRecorder $recorder)
    {
        $this->beConstructedWith($eventBus, $recorder);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(DispatchEventsWorker::class);
        $this->shouldBeAnInstanceOf(Worker::class);
    }

    public function it_dispatches_events($eventBus, $recorder, Event $event1, Event $event2, Message $message)
    {
        $recorder->shift()->shouldBeCalled(3)->willReturn($event1, $event2, null);
        $eventBus->dispatch(Argument::type(Event::class))->shouldBeCalled(2);
        $this->work($message);
    }
}
