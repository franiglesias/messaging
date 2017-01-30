<?php

namespace Test\Messagins\Integrations;

use Milhojas\Messaging\CommandBus\CommandBus;
use Milhojas\Messaging\CommandBus\Command\BroadcastEvent;
use Milhojas\Messaging\CommandBus\Command\BroadcastEventHandler;
use Milhojas\Messaging\CommandBus\Worker\ExecuteWorker;
use Milhojas\Messaging\EventBus\Listener;
use Milhojas\Messaging\EventBus\EventBus;
use Milhojas\Messaging\EventBus\Event;
use Milhojas\Messaging\EventBus\EventRecorder;
use Milhojas\Messaging\EventBus\Worker\DispatcherWorker;
use Milhojas\Messaging\Shared\Inflector\Inflector;
use Milhojas\Messaging\EventBus\Inflector\ListenerInflector;
use Milhojas\Messaging\Shared\Loader\TestLoader;
use Milhojas\Messaging\Shared\Pipeline\WorkerPipeline;
use Milhojas\Messaging\Shared\Worker\LoggerWorker;
use Milhojas\Messaging\Shared\Worker\DispatchEventsWorker;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

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

class TestInflector implements Inflector
{
    public function inflect($className)
    {
        return $className;
    }
}

/**
 * Examines a possible interaction between the command bus dispatching events and the event bus.
 */
class CommandBusEventBusIntegrationsTest extends TestCase
{
    private $logger,
        $eventBus,
        $commandBus,
        $loader,
        $inflector,
        $loggerWorker
        ;

    public function setUp()
    {
        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->loggerWorker = new LoggerWorker($this->logger->reveal());

        $this->loader = new TestLoader();
        $this->inflector = new TestInflector();

        $this->recorder = new EventRecorder();
        $this->setUpEventBus();
        $this->setUpCommandBus();
    }

    private function setUpEventBus()
    {
        $this->listener = new EventListenerForTest();
        $inflector = new ListenerInflector();
        $inflector->addListener('test.event', 'test.event.listener');

        $this->loader->add('test.event.listener', $this->listener);

        $eventBusPipeline = new WorkerPipeline([
            new DispatcherWorker($inflector, $this->loader),
            $this->loggerWorker,
        ]);
        $this->eventBus = new EventBus($eventBusPipeline);
    }

    private function setUpCommandBus()
    {
        $event = $this->prophesize(Event::class);
        $event->getName()->willReturn('test.event');
        $this->broadcast = new BroadcastEvent($event->reveal());
        $this->broadcastHandler = new BroadcastEventHandler($this->recorder);
        $this->loader->add(get_class($this->broadcast), $this->broadcastHandler);
        $commandBusPipeline = new WorkerPipeline([
            new ExecuteWorker($this->loader, $this->inflector),
            $this->loggerWorker,
            new DispatchEventsWorker($this->eventBus, $this->recorder),
        ]);
        $this->commandBus = new CommandBus($commandBusPipeline);
    }

    public function no_test_event_bus_is_working()
    {
        $event = $this->prophesize(Event::class);
        $event->getName()->willReturn('test.event');
        $this->eventBus->dispatch($event->reveal());
        $this->assertEquals('test.event', $this->listener->spy);
    }

    public function test_command_bus_executing_broadcast_event()
    {
        $this->commandBus->execute($this->broadcast);
        $this->assertEquals('test.event', $this->listener->spy);
    }
}
