<?php

namespace Milhojas\Messaging\CommandBus;

use Milhojas\Messaging\Shared\Worker\Worker;

/**
 * A very Basic Command Bus that builds a chain of responsibility with an array of pipeline.
 */
class CommandBus
{
    protected $pipeline;

    public function __construct(Worker $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * Execute command.
     *
     * @param Command $command
     *
     * @author Fran Iglesias
     */
    public function execute(Command $command)
    {
        $this->pipeline->work($command);
    }
}
