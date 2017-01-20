<?php

namespace spec\Milhojas\Messaging\CommandBus;

use Milhojas\Messaging\CommandBus\CommandBus;
use Milhojas\Messaging\CommandBus\Command;
use Milhojas\Messaging\Shared\Pipeline\Pipeline;
use Milhojas\Messaging\Shared\Worker\Worker;
use PhpSpec\ObjectBehavior;

class CommandBusSpec extends ObjectBehavior
{
    public function let(Pipeline $pipeline)
    {
        $this->beConstructedWith($pipeline);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(CommandBus::class);
    }

    public function it_dispatches_commands_through_the_pipeline(Command $command, $pipeline)
    {
        $pipeline->work($command)->shouldBeCalled();
        $this->execute($command);
    }

    public function it_accepts_a_unique_worker(Worker $worker, Command $command)
    {
        $this->beConstructedWith($worker);
        $worker->work($command)->shouldBeCalled();
        $this->execute($command);
    }
}
