<?php

namespace Test\Messaging\Integrations;

use PHPUnit\Framework\TestCase;
use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\EventBus\Listener;
use Milhojas\Messaging\EventBus\EventBus;
use Milhojas\Messaging\EventBus\Worker\DispatcherWorker;
use Milhojas\Messaging\EventBus\Inflector\ListenerInflector;
use Milhojas\Messaging\Shared\Loader\TestLoader;

class EventListenerForTest implements Listener
{
    public $spy;
    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        $this->spy = $event->getName();
    }
}

class EventBusConfigurationTest extends TestCase
{
    private $bus;
    private $listener;

    public function setUp()
    {
        $inflector = $this->getInflector();
        $this->listener = new EventListenerForTest();
        $loader = $this->getLoader();
        $this->bus = new EventBus(new DispatcherWorker($inflector, $loader));
    }

    /**
     * @return TestLoader
     */
    private function getLoader()
    {
        $loader = new TestLoader();
        $loader->add('test.event.listener', $this->listener);

        return $loader;
    }

    /**
     * @return ListenerInflector
     */
    private function getInflector()
    {
        $inflector = new ListenerInflector();
        $inflector->addListener('test.event', 'test.event.listener');

        return $inflector;
    }

    public function test_it_handles_an_event()
    {
        $event = $this->prophesize(Event::class);
        $event->getName()->willReturn('test.event');

        $this->bus->dispatch($event->reveal());
        $this->assertEquals('test.event', $this->listener->spy);
    }
}
