<?php

namespace spec\Milhojas\Messaging\EventBus\Worker;

use Milhojas\Messaging\EventBus\Loader\ListenerLoader;
use Milhojas\Messaging\EventBus\Worker\DispatcherWorker;
use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\EventBus\Listener;
use Milhojas\Messaging\Shared\Worker\Worker;
use PhpSpec\ObjectBehavior;

class DispatcherWorkerSpec extends ObjectBehavior
{
    public function let(ListenerLoader $loader)
    {
        $this->beConstructedWith($loader);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(DispatcherWorker::class);
        $this->shouldImplement(Worker::class);
    }

    public function it_dispatches_event_to_one_handler(Event $event, Listener $listener, $loader)
    {
        $event->getName()->shouldBeCalled()->willReturn('event');
        $loader->get('event')->shouldBeCalled()->willReturn([$listener]);
        $listener->handle($event)->shouldBeCalled();
        $this->work($event);
    }

    public function it_dispatches_event_to_several_handlers(Event $event, Listener $listener, Listener $listener2, $loader)
    {
        $event->getName()->shouldBeCalled()->willReturn('event');
        $loader->get('event')->shouldBeCalled()->willReturn([$listener, $listener2]);
        $listener->handle($event)->shouldBeCalled();
        $listener2->handle($event)->shouldBeCalled();
        $this->work($event);
    }

    public function it_ignores_events_without_handlers(Event $event, Listener $listener, $loader)
    {
        $event->getName()->shouldBeCalled()->willReturn('event');
        $loader->get('event')->shouldBeCalled()->willReturn([]);
        $listener->handle($event)->shouldNotBeCalled();
        $this->work($event);
    }
}
