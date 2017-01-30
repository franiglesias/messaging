<?php

namespace spec\Milhojas\Messaging\EventBus\Inflector;

use Milhojas\Messaging\EventBus\Inflector\ListenerInflector;
use Milhojas\Messaging\Shared\Inflector\Inflector;
use Milhojas\Messaging\Shared\Exception\InvalidLoaderKey;
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

    public function it_throws_exception_when_asked_for_non_existent_key()
    {
        $this->shouldThrow(InvalidLoaderKey::class)->during('inflect', ['false.event.test']);
    }
}
