<?php

namespace spec\Milhojas\Messaging\CommandBus\Worker;

use Milhojas\Messaging\CommandBus\Command;
use Milhojas\Messaging\CommandBus\CommandHandler;
use Milhojas\Messaging\Shared\Loader\Loader;
use Milhojas\Messaging\Shared\Inflector\Inflector;
use Milhojas\Messaging\CommandBus\Worker\ExecuteWorker;
use PhpSpec\ObjectBehavior;

class ExecuteWorkerSpec extends ObjectBehavior
{
    public function let(Loader $loader, Inflector $inflector)
    {
        $this->beConstructedWith($loader, $inflector);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ExecuteWorker::class);
    }

    public function it_can_execute_command_calling_the_right_handler(Command $command, CommandHandler $handler, $loader, $inflector)
    {
        $inflector->inflect(get_class($command->getWrappedObject()))->shouldBeCalled()->willReturn('handlerClass');
        $loader->get('handlerClass')->shouldBeCalled()->willReturn($handler);
        $handler->handle($command)->shouldBeCalled();
        $this->work($command);
    }
}
