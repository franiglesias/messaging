<?php

namespace Milhojas\Messaging\EventBus\Worker;

use Milhojas\Messaging\EventBus\Loader\ListenerLoader;
use Milhojas\Messaging\Shared\Worker\Worker;
use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\EventBus\Event;

class DispatcherWorker implements Worker
{
    private $loader;

    public function __construct(ListenerLoader $loader)
    {
        $this->loader = $loader;
    }

    public function work(Message $event)
    {
        $listeners = $this->getListeners($event);
        foreach ($listeners as $listener) {
            $listener->handle($event);
        }
    }

    public function getListeners(Event $event)
    {
        return $this->loader->get($event->getName());
    }
}
