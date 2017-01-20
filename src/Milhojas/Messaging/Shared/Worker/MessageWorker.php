<?php

namespace Milhojas\Messaging\Shared\Worker;

use Milhojas\Messaging\Shared\Message;

/**
 * A message Worker does something with the Message or about it
 * MessageWorkers can be chained in a Pipeline.
 */
abstract class MessageWorker implements Worker
{
    /**
     * the MessageWorker to which pass the Message when I am finished.
     *
     * @var MessageWorker
     */
    protected $next;
    /**
     * the result of the handling of a message if needed.
     *
     * @var mixed
     */
    protected $result;

    /**
     * Do somenthing with the Message. Usually pass it to a Message Handler.
     *
     * @param Message $message
     */
    abstract public function execute(Message $message);

    /**
     * Processes the command and pass it along to the next worker in the chain.
     * If a.
     *
     * @param Message $message
     */
    public function work(Message $message)
    {
        $result = $this->execute($message);
        if ($result && !$this->result) {
            $this->result = $result;
        }

        return $this->delegate($message);
    }

    /**
     * Sets the following worker in the chain.
     *
     * @param MessageWorker $next
     */
    public function chain(MessageWorker $next)
    {
        if ($this->isTheLastWorkerInChain()) {
            $this->next = $next;

            return;
        }
        $this->next->chain($next);
    }

    /**
     * @return bool true if this is the last Worker in the CoR
     */
    protected function isTheLastWorkerInChain()
    {
        return !$this->next;
    }

    /**
     * Pass the command to the next Worker in the chain.
     *
     * @param Message $message
     */
    protected function delegate(Message $message)
    {
        if (!$this->next) {
            return $this->result;
        }

        return $this->next->work($message);
    }
}
