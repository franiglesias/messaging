<?php

namespace Milhojas\Messaging\EventBus\Inflector;

use Milhojas\Messaging\Shared\Inflector\Inflector;

class ListenerInflector implements Inflector
{
    private $map = [];
    /**
     * {@inheritdoc}
     */
    public function inflect($eventKey)
    {
        if (!$this->has($eventKey)) {
            return [];
        }

        return $this->map[$eventKey];
    }

    public function addListener($eventKey, $listenerKey)
    {
        $this->map[$eventKey][] = $listenerKey;
    }

    private function has($eventKey)
    {
        return isset($this->map[$eventKey]);
    }

    public function addListeners($eventKey, array $listeners)
    {
        foreach ($listeners as $listenerKey) {
            $this->addListener($eventKey, $listenerKey);
        }
    }
}
