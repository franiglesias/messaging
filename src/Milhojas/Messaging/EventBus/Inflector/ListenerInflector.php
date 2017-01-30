<?php

namespace Milhojas\Messaging\EventBus\Inflector;

use Milhojas\Messaging\Shared\Inflector\Inflector;
use Milhojas\Messaging\Shared\Exception\InvalidLoaderKey;

class ListenerInflector implements Inflector
{
    private $map = [];
    /**
     * {@inheritdoc}
     */
    public function inflect($eventKey)
    {
        $this->has($eventKey);

        return $this->map[$eventKey];
    }

    public function addListener($eventKey, $listenerKey)
    {
        $this->map[$eventKey][] = $listenerKey;
    }

    private function has($eventKey)
    {
        if (!isset($this->map[$eventKey])) {
            throw new InvalidLoaderKey(sprintf('There are no Listener(s) to handle %s event', $eventKey));
        }
    }

    public function addListeners($eventKey, array $listeners)
    {
        foreach ($listeners as $listenerKey) {
            $this->addListener($eventKey, $listenerKey);
        }
    }
}
