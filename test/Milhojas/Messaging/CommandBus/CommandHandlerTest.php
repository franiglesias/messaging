<?php

namespace Test\Messaging\CommandBus;

use PHPUnit\Framework\TestCase;
use Milhojas\Messaging\CommandBus\CommandHandler;
use Milhojas\Messaging\CommandBus\Command;

class ConcreteHandler implements CommandHandler
{
    public $spy;
    /**
     * {@inheritdoc}
     */
    public function handle(ConcreteCommand $command)
    {
        $this->spy = $command->getData();
    }
}

class ConcreteCommand implements Command
{
    public function getData()
    {
        return 'data';
    }
}

class CommandHandlerTest extends TestCase
{
    public function setUp()
    {
        $this->handler = new ConcreteHandler();
    }
    public function test_it_handles_concrete_command()
    {
        $command = new ConcreteCommand();
        $this->handler->handle($command);
        $this->assertEquals('data', $this->handler->spy);
    }
}
