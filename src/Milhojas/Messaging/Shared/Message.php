<?php

namespace Milhojas\Messaging\Shared;

/**
 * Message is a marker interface that represents a basic type of Message that can be dispatched through a MessageBus
 * Messages are Immutable objects. Its name reflects intent, properties carry data for the interested handlers.
 * Command, Query and Event interfaces extend Message interface.
 */
interface Message
{
}
