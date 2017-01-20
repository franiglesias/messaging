<?php

namespace Milhojas\Messaging\EventBus\Loader;

use Milhojas\Messaging\EventBus\Listener;
use Milhojas\Messaging\Shared\Exception\InvalidLoaderKey;
use Milhojas\Messaging\Shared\Loader\Loader;

class ListenerLoader implements Loader
{
    private $listeners = [];

    public function addListener($event_name, Listener $listener)
    {
        $this->listeners[$event_name][] = $listener;
    }

    public function addListeners($event_name, array $listeners)
    {
        foreach ($listeners as $listener) {
            $this->addListener($event_name, $listener);
        }
    }

    public function get($event_name)
    {
        $this->canManageEvent($event_name);

        return $this->listeners[$event_name];
    }

    private function canManageEvent($event_name)
    {
        if (!isset($this->listeners[$event_name])) {
            throw new InvalidLoaderKey(sprintf('Event %s has not been defined.', $event_name));
        }
    }
}
