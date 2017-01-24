<?php

namespace Milhojas\Messaging\CommandBus;

/**
 * A class to manage a Command.
 */
interface CommandHandler
{
    public function handle(Command $command);
}
