<?php

namespace spec\Milhojas\Messaging\Shared\Loader;

use Milhojas\Messaging\QueryBus\QueryHandler;
use Milhojas\Messaging\Shared\Loader\TestLoader;
use Milhojas\Messaging\Shared\Loader\Loader;
use Milhojas\Messaging\Shared\Exception\InvalidLoaderKey;
use PhpSpec\ObjectBehavior;

class TestLoaderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TestLoader::class);
        $this->shouldImplement(Loader::class);
    }

    public function it_loads_class_given_by_name(QueryHandler $handler)
    {
        $this->add('context.my_query.handler', $handler);
        $this->get('context.my_query.handler')->shouldBe($handler);
    }

    public function it_throws_exception_if_no_handler_found()
    {
        $this->shouldThrow(InvalidLoaderKey::class)->during('get', ['context.my_query.handler']);
    }
}
