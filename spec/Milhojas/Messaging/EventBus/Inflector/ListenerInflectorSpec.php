<?php

namespace spec\Milhojas\Messaging\EventBus\Inflector;

use Milhojas\Messaging\EventBus\Inflector\ListenerInflector;
use Milhojas\Messaging\Shared\Inflector\Inflector;
use PhpSpec\ObjectBehavior;

class ListenerInflectorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ListenerInflector::class);
        $this->shouldImplement(Inflector::class);
    }

    public function it_can_add_a_listener_key()
    {
        $event = 'event.test';
        $listener = 'event.listener';
        $this->addListener('event.test', 'event.listener');
        $this->inflect('event.test')->shouldBe(['event.listener']);
    }

    public function it_can_add_several_listeners_in_one_call()
    {
        $event = 'event.test';
        $listeners = ['event.listener', 'another.event.listener'];
        $this->addListeners('event.test', $listeners);
        $this->inflect('event.test')->shouldBe($listeners);
    }

    public function it_return_empty_array_if_event_is_not_managed()
    {
        $this->inflect('false.event.test')->shouldBe([]);
    }
}
