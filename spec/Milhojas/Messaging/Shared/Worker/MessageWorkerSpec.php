<?php

namespace spec\Milhojas\Messaging\Shared\Worker;

use spec;
use Milhojas\Messaging\Shared\Worker\MessageWorker;
use Milhojas\Messaging\Shared\Message;
use PhpSpec\ObjectBehavior;

class MessageWorkerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(spec\Milhojas\Messaging\Shared\Worker\TestWorker::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(MessageWorker::class);
    }

    public function it_can_chain_another_worker_and_delegates_execution(MessageWorker $worker, Message $message)
    {
        $worker->work($message)->shouldBeCalled();
        $this->chain($worker);
        $this->work($message);
    }

    public function it_chains_workers_at_the_end_of_the_chain(MessageWorker $worker, MessageWorker $worker2)
    {
        $this->chain($worker);
        $worker->chain($worker2)->shouldBeCalled();
        $this->chain($worker2);
    }

    public function it_return_response_if_it_was_generated_by_delegated_worker(Message $message, MessageWorker $worker)
    {
        $worker->work($message)->shouldBeCalled()->willReturn('A reponse');
        $this->chain($worker);
        $this->work($message)->shouldBe('A reponse');
    }
}

class TestWorker extends MessageWorker
{
    /**
     * {@inheritdoc}
     */
    public function execute(Message $message)
    {
        $this->message = $message;
    }
}
