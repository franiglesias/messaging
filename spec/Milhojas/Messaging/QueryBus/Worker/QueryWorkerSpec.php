<?php

namespace spec\Milhojas\Messaging\QueryBus\Worker;

use Milhojas\Messaging\QueryBus\QueryHandler;
use Milhojas\Messaging\QueryBus\Query;
use Milhojas\Messaging\QueryBus\Worker\QueryWorker;
use Milhojas\Messaging\Shared\Worker\MessageWorker;
use PhpSpec\ObjectBehavior;
use Milhojas\Messaging\Shared\Loader\Loader;
use Milhojas\Messaging\Shared\Inflector\Inflector;
use Prophecy\Argument;

class QueryWorkerSpec extends ObjectBehavior
{
    public function let(Loader $loader, Inflector $inflector)
    {
        $this->beConstructedWith($loader, $inflector);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(QueryWorker::class);
        $this->shouldBeAnInstanceOf(MessageWorker::class);
    }

    public function it_performs_query_and_returns_result(Query $query, QueryHandler $handler, $loader, $inflector)
    {
        $inflector->inflect(Argument::type('string'))->shouldBeCalled()->willReturn('Handler');
        $loader->get('Handler')->shouldBeCalled()->willReturn($handler);
        $handler->answer($query)->shouldBeCalled()->willReturn('A result');
        $this->execute($query)->shouldBe('A result');
    }
}
