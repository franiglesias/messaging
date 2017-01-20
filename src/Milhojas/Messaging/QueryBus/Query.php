<?php

namespace Milhojas\Messaging\QueryBus;

use Milhojas\Messaging\Shared\Message;

/**
 * A simple DTO that represents an interrogative message to the system
 * A query is answered by a QueryHandler that has an answer method that returns the response of throws an exception if something goes wrong.
 */
interface Query extends Message
{
}
