<?php

namespace Milhojas\Messaging\CommandBus\Worker;

use Milhojas\Messaging\Shared\Loader\Loader;
use Milhojas\Messaging\Shared\Inflector\Inflector;
use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Worker\Worker;
use Milhojas\Messaging\CommandBus\Command;

/**
 * Manages the execution of a command with the right command handler
 * You can control behavior using different inflectors.
 */
class ExecuteWorker implements Worker
{
    /**
     * Loads the handler given a key derived by INflector from Command.
     *
     * @var Loader
     */
    private $loader;
    /**
     * Guess the key to load the Handler.
     *
     * @var mixed
     */
    private $inflector;

    /**
     * @param Loader    $loader
     * @param Inflector $inflector
     */
    public function __construct(Loader $loader, Inflector $inflector)
    {
        $this->loader = $loader;
        $this->inflector = $inflector;
    }

    /**
     * Execute the needed handler and pass the comnand to the next Worker.
     *
     * @param Message $command
     */
    public function work(Message $command)
    {
        $handler = $this->getHandler($command);
        $handler->handle($command);
    }

    /**
     * Resolves the handler for this commnad.
     *
     * @param Command $command
     */
    protected function getHandler(Command $command)
    {
        $handler = $this->inflector->inflect(get_class($command));

        return $this->loader->get($handler);
    }
}
