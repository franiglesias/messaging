<?php

namespace Milhojas\Messaging\CommandBus;

use Milhojas\Messaging\Shared\Message;

/**
 * Represents an Imperative message to the system: DoSomething
 * Doesn't return data. Only records Events or throws exceptions.
 */
interface Command extends Message
{
}
