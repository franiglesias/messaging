<?php

namespace Milhojas\Messaging\EventBus;

use Milhojas\Messaging\Shared\Worker\Worker;

class EventBus
{
    private $pipeline;

    public function __construct(Worker $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    public function dispatch(Event $event)
    {
        $this->pipeline->work($event);
    }
}
