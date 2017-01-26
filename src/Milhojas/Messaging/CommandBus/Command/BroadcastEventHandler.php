<?php

namespace Milhojas\Messaging\CommandBus\Command;

use Milhojas\Messaging\CommandBus\Command;
use Milhojas\Messaging\CommandBus\CommandHandler;
use Milhojas\Messaging\EventBus\EventRecorder;

/**
 * Broadcasts an event.
 *
 * Handles the broadcasting of an event
 */
class BroadcastEventHandler implements CommandHandler
{
    private $recorder;

    public function __construct(EventRecorder $recorder)
    {
        $this->recorder = $recorder;
    }

    public function handle(Command $command)
    {
        $this->recorder->recordThat($command->getEvent());
    }
}
