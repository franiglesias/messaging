<?php

namespace spec\Milhojas\Messaging\Shared\Pipeline;

use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Pipeline\Pipeline;
use Milhojas\Messaging\Shared\Pipeline\WorkerPipeline;
use Milhojas\Messaging\Shared\Worker\Worker;
use Milhojas\Messaging\Shared\Exception\EmptyPipeline;
use PhpSpec\ObjectBehavior;

class WorkerPipelineSpec extends ObjectBehavior
{
    public function let(Worker $worker1, Worker $worker2)
    {
        $this->beConstructedWith([$worker1, $worker2]);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(WorkerPipeline::class);
        $this->shouldImplement(Pipeline::class);
    }

    public function it_works_with_a_message(Message $message, $worker1)
    {
        $worker1->work($message)->shouldBeCalled();
        $this->work($message);
    }

    public function it_works_with_a_message_and_returns_a_reponse(Message $message, $worker1)
    {
        $worker1->work($message)->shouldBeCalled()->willReturn('Response');
        $this->work($message)->shouldBe('Response');
    }

    public function it_throws_exception_if_no_workers()
    {
        $this->beConstructedWith([]);
        $this->shouldThrow(EmptyPipeline::class)->duringInstantiation();
    }

    public function it_passes_message_to_all_workers(Message $message, Worker $worker1, Worker $worker2)
    {
        $worker1->work($message)->shouldBeCalled();
        $worker2->work($message)->shouldBeCalled();
        $this->work($message);
    }
}
