<?php

namespace spec\Milhojas\Messaging\Shared\Loader;

use Milhojas\Messaging\Shared\Loader\SymfonyContainerLoader;
use Milhojas\Messaging\Shared\Loader\Loader;
use Milhojas\Messaging\QueryBus\QueryHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PhpSpec\ObjectBehavior;

class SymfonyContainerLoaderSpec extends ObjectBehavior
{
    public function let(ContainerInterface $container)
    {
        $this->beConstructedWith();
        $this->setContainer($container);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(SymfonyContainerLoader::class);
        $this->shouldImplement(Loader::class);
    }

    public function it_loads_class_given_by_name($container, QueryHandler $handler)
    {
        $container->get('context.my_query.handler')->willReturn($handler);
        $this->get('context.my_query.handler')->shouldBe($handler);
    }
}
