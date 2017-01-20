<?php

namespace spec\Milhojas\Messaging\EventBus;

use Milhojas\Messaging\EventBus\EventRecorder;
use Milhojas\Messaging\EventBus\Event;
use PhpSpec\ObjectBehavior;

class EventRecorderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith();
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(EventRecorder::class);
    }

    public function it_records_events(Event $event)
    {
        $this->recordThat($event);
        $this->shouldHaveCount(1);
    }

    public function it_load_several_events_at_once(Event $event1, Event $event2)
    {
        $this->load([$event1, $event2]);
        $this->shouldHaveCount(2);
    }

    public function it_gives_all_events(Event $event1, Event $event2)
    {
        $this->load([$event1, $event2]);
        $this->retrieve()->shouldBe([$event1, $event2]);
    }

    public function it_can_forget_events(Event $event1, Event $event2)
    {
        $this->load([$event1, $event2]);
        $this->flush();
        $this->shouldHaveCount(0);
    }
}
