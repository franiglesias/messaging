<?php

namespace Milhojas\Messaging\Shared\Pipeline;

use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Worker\MessageWorker;
use Milhojas\Messaging\Shared\Exception\EmptyPipeline;

/**
 * Pipeline of Workers.
 * Creates a Chain of Responsibility for workers.
 */
class WorkerPipeline implements Pipeline
{
    private $pipeline;

    /**
     * Builds a CoR with the array of workers.
     *
     * @param MessageWorker[] $workers
     */
    public function __construct(array $workers)
    {
        if (!$workers) {
            throw new EmptyPipeline('A pipeline needs MessageWorkers to work.');
        }
        $this->pipeline = $this->build($workers);
    }

    /**
     * Builds the responsibility chain.
     *
     * @param MessageWorker[] $workers
     *
     * @return MessageWorker the chain
     *
     * @author Francisco Iglesias Gómez
     */
    protected function build($workers)
    {
        $chain = array_shift($workers);
        while ($workers) {
            $chain->chain(array_shift($workers));
        }

        return $chain;
    }

    /**
     * {@inheritdoc}
     */
    public function work(Message $message)
    {
        return $this->pipeline->work($message);
    }
}
