<?php

namespace Milhojas\Messaging\EventBus;

interface Listener
{
    public function handle(Event $event);
}
