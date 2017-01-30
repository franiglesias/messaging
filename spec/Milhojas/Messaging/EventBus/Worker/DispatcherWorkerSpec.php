<?php

namespace spec\Milhojas\Messaging\EventBus\Worker;

use Milhojas\Messaging\EventBus\Worker\DispatcherWorker;
use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\EventBus\Listener;
use Milhojas\Messaging\Shared\Worker\Worker;
use Milhojas\Messaging\Shared\Inflector\Inflector;
use Milhojas\Messaging\Shared\Loader\Loader;
use PhpSpec\ObjectBehavior;

class DispatcherWorkerSpec extends ObjectBehavior
{
    public function let(Inflector $inflector, Loader $loader)
    {
        $this->beConstructedWith($inflector, $loader);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(DispatcherWorker::class);
        $this->shouldImplement(Worker::class);
    }

    public function it_dispatches_event_to_one_handler(Event $event, Listener $listener, $inflector, $loader)
    {
        $event->getName()->shouldBeCalled()->willReturn('event');
        $inflector->inflect('event')->shouldBeCalled()->willReturn(['listener']);
        $loader->get('listener')->shouldBeCalled()->willReturn($listener);
        $listener->handle($event)->shouldBeCalled();
        $this->work($event);
    }

    public function it_dispatches_event_to_several_handlers(Event $event, Listener $listener, Listener $listener2, $inflector, $loader)
    {
        $event->getName()->shouldBeCalled()->willReturn('event');
        $inflector->inflect('event')->shouldBeCalled()->willReturn(['listener', 'listener2']);
        $loader->get('listener')->shouldBeCalled(3)->willReturn($listener);
        $loader->get('listener2')->shouldBeCalled(3)->willReturn($listener2);
        $listener->handle($event)->shouldBeCalled();
        $listener2->handle($event)->shouldBeCalled();
        $this->work($event);
    }

    public function it_ignores_events_without_handlers(Event $event, Listener $listener, $inflector, $loader)
    {
        $event->getName()->shouldBeCalled()->willReturn('event');
        $inflector->inflect('event')->shouldBeCalled()->willReturn([]);

        $loader->get('listener')->shouldNotBeCalled();
        $listener->handle($event)->shouldNotBeCalled();
        $this->work($event);
    }
}
