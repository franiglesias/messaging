<?php

namespace Milhojas\Messaging\CommandBus;

/**
 * A very Basic Command Bus that simply registes the commands received.
 */
class TestCommandBus extends CommandBus
{
    protected $commands;

    public function __construct()
    {
        $this->commands = array();
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
        $this->commands[] = get_class($command);
    }

    /**
     * Checks if a command was receiven $times times.
     *
     * @param string $command
     * @param string $times   default 1
     *
     * @return bool
     *
     * @author Fran Iglesias
     */
    public function wasReceived($command, $times = 1)
    {
        $stat = array_count_values($this->commands);

        return $stat[$command] === $times;
    }

    public function getReceived()
    {
        return $this->commands;
    }
}
