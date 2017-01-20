<?php

namespace Milhojas\Messaging\EventBus;

use Milhojas\Messaging\Shared\Message;

/**
 * Represents a declarative message about something that had happened in the app domain.
 */
interface Event extends Message
{
    /**
     * The name that identifies this event.
     *
     * @return string a name context.event
     */
    public function getName();
}
