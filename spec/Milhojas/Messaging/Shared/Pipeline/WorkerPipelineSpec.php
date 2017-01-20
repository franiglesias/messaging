<?php

namespace spec\Milhojas\Messaging\Shared\Pipeline;

use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Pipeline\Pipeline;
use Milhojas\Messaging\Shared\Pipeline\WorkerPipeline;
use Milhojas\Messaging\Shared\Worker\MessageWorker;
use Milhojas\Messaging\Shared\Exception\EmptyPipeline;
use PhpSpec\ObjectBehavior;

class WorkerPipelineSpec extends ObjectBehavior
{
    public function let(MessageWorker $worker1, MessageWorker $worker2)
    {
        $worker1->chain($worker2)->shouldBeCalled();
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

    public function it_throws_exception_if_no_workers($worker1, $worker2)
    {
        $worker1->chain($worker2)->shouldNotBeCalled();
        $this->beConstructedWith([]);
        $this->shouldThrow(EmptyPipeline::class)->duringInstantiation();
    }
}
