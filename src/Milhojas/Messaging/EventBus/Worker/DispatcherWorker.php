<?php

namespace Milhojas\Messaging\EventBus\Worker;

use Milhojas\Messaging\Shared\Worker\Worker;
use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Loader\Loader;
use Milhojas\Messaging\Shared\Inflector\Inflector;

class DispatcherWorker implements Worker
{
    private $inflector;
    private $loader;

    public function __construct(Inflector $inflector, Loader $loader)
    {
        $this->inflector = $inflector;
        $this->loader = $loader;
    }

    public function work(Message $event)
    {
        $listeners = $this->inflector->inflect($event->getName());
        foreach ($listeners as $listenerKey) {
            $listener = $this->loader->get($listenerKey);
            $listener->handle($event);
        }
    }
}
