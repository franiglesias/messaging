<?php

namespace spec\Milhojas\Messaging\CommandBus\Command;

use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\CommandBus\Command\BroadcastEvent;
use Milhojas\Messaging\CommandBus\Command\BroadcastEventListener;
use Milhojas\Messaging\EventBus\EventRecorder;
use PhpSpec\ObjectBehavior;

class BroadcastEventListenerSpec extends ObjectBehavior
{
    public function let(EventRecorder $eventRecorder)
    {
        $this->beConstructedWith($eventRecorder);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(BroadcastEventListener::class);
    }

    public function it_handles_BroadcastEvent_commands(BroadcastEvent $broadcastEvent, $eventRecorder, Event $event)
    {
        $broadcastEvent->getEvent()->shouldBeCalled()->willReturn($event);
        $eventRecorder->recordThat($event)->shouldBeCalled();
        $this->handle($broadcastEvent);
    }
}
