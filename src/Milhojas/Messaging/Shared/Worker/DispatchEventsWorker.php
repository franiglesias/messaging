<?php

namespace Milhojas\Messaging\Shared\Worker;

use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\EventBus\EventBus;
use Milhojas\Messaging\EventBus\EventRecorder;

/**
 * Collects events and dispatches them
 * Must be at the end of the chain.
 */
class DispatchEventsWorker extends MessageWorker
{
    /**
     * Needed to dispatch events.
     *
     * @var EventBus
     */
    private $eventBus;
    /**
     * Collect events to dispatch.
     *
     * @var mixed
     */
    private $recorder;

    public function __construct(EventBus $eventBus, EventRecorder $recorder)
    {
        $this->eventBus = $eventBus;
        $this->recorder = $recorder;
    }

    /**
     * Dispatches events collected in EventRecorder.
     * Clones and flushes the current recorder and uses a temp recorder to dispatch events
     * This avoid an infinite loop condition.
     *
     * @param Message $message
     */
    public function execute(Message $message)
    {
        while ($event = $this->recorder->shift()) {
            $this->eventBus->dispatch($event);
        }
    }

    /**
     * Forces this Worker to be the last in the workers ChainCache.
     *
     * @param MessageWorker $worker a worker to chain after this Worker
     *
     * @throws \InvalidArgumentException [description]
     */
    public function chain(MessageWorker $worker)
    {
        throw new \InvalidArgumentException('DispatchEventsWorker should be the last worker in the chain');
    }
}
