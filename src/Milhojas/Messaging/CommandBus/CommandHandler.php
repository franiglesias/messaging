<?php

namespace Milhojas\Messaging\CommandBus;

interface CommandHandler
{
    public function handle(Command $command);
}
