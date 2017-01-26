<?php

namespace spec\Milhojas\Messaging\Shared\Worker;

use spec;
use Milhojas\Messaging\Shared\Worker\MessageWorker;
use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Exception\WorkerInstancePreviouslyUsed;
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
        $worker->hasAnotherWorkerChained()->shouldBeCalled()->willReturn(false);
        $worker->work($message)->shouldBeCalled();
        $this->chain($worker);
        $this->work($message);
    }

    public function it_chains_workers_at_the_end_of_the_chain(MessageWorker $worker, MessageWorker $worker2)
    {
        $worker->hasAnotherWorkerChained()->shouldBeCalled()->willReturn(false);
        $worker2->hasAnotherWorkerChained()->shouldBeCalled()->willReturn(false);
        $this->chain($worker);
        $worker->chain($worker2)->shouldBeCalled();
        $this->chain($worker2);
    }

    public function it_return_response_if_it_was_generated_by_delegated_worker(Message $message, MessageWorker $worker)
    {
        $worker->hasAnotherWorkerChained()->shouldBeCalled()->willReturn(false);
        $worker->work($message)->shouldBeCalled()->willReturn('A reponse');
        $this->chain($worker);
        $this->work($message)->shouldBe('A reponse');
    }

    public function it_can_say_if_it_has_chained_workers(MessageWorker $worker)
    {
        $this->shouldNotHaveAnotherWorkerChained();
        $this->chain($worker);
        $this->shouldHaveAnotherWorkerChained();
    }
    public function it_forbids_to_chain_a_previously_chained_worker(MessageWorker $worker)
    {
        $worker->hasAnotherWorkerChained()->shouldBeCalled()->willReturn(true);
        $this->shouldThrow(WorkerInstancePreviouslyUsed::class)->during('chain', [$worker]);
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
