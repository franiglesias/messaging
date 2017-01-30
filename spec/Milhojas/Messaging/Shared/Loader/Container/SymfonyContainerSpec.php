<?php

namespace spec\Milhojas\Messaging\Shared\Loader\Container;

use Milhojas\Messaging\Shared\Loader\Container\SymfonyContainer;
use PhpSpec\ObjectBehavior;
use Interop\Container\ContainerInterface;
use Symfony\Component\DependencyInjection as Symfony;

class SymfonyContainerSpec extends ObjectBehavior
{
    public function let(Symfony\ContainerInterface $container)
    {
        $this->beConstructedWith();
        $this->setContainer($container);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(SymfonyContainer::class);
        $this->shouldImplement(ContainerInterface::class);
    }

    public function it_load_class($container, $something)
    {
        $container->get('key')->shouldBeCalled()->willReturn($something);
        $this->get('key')->shouldBe($something);
    }

    public function it_knows_it_can_load_class($container)
    {
        $container->has('key')->shouldBeCalled()->willReturn(true);
        $this->has('key')->shouldBe(true);
    }
}
