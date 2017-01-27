<?php

namespace Milhojas\Messaging\Shared\Pipeline;

use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Worker\Worker;
use Milhojas\Messaging\Shared\Exception\EmptyPipeline;

/**
 * Pipeline of Workers.
 * Creates a Chain of Responsibility for workers.
 */
class WorkerPipeline implements Pipeline
{
    /**
     * Stores a queue of workers.
     *
     * @var \SplQueue
     */
    private $queue;
    /**
     * Result returned from a worker.
     *
     * @var mixed
     */
    private $result = null;
    /**
     * Builds a queue with the array of workers.
     *
     * @param Worker[] $workers
     */
    public function __construct(array $workers)
    {
        if (!$workers) {
            throw new EmptyPipeline('A pipeline needs MessageWorkers to work.');
        }
        $this->queue = new \SplQueue();
        while ($workers) {
            $this->enqueue(array_shift($workers));
        }
    }

    /**
     * Add an element to the Worker chain.
     *
     * @param Worker $worker
     *
     * @author Francisco Iglesias Gómez
     */
    protected function enqueue(Worker $worker)
    {
        $this->queue->enqueue($worker);
    }

    /**
     * {@inheritdoc}
     */
    public function work(Message $message)
    {
        foreach ($this->queue as $worker) {
            $result = $worker->work($message);
            if ($result && !$this->result) {
                $this->result = $result;
            }
        }

        return $this->result;
    }
}
