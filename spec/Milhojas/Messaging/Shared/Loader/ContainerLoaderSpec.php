<?php

namespace spec\Milhojas\Messaging\Shared\Loader;

use Milhojas\Messaging\Shared\Exception\InvalidLoaderKey;
use Milhojas\Messaging\Shared\Loader\Loader;
use Milhojas\Messaging\Shared\Loader\ContainerLoader;
use Interop\Container\ContainerInterface;
use PhpSpec\ObjectBehavior;

class ContainerLoaderSpec extends ObjectBehavior
{
    public function let(ContainerInterface $container)
    {
        $this->beConstructedWith($container);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(ContainerLoader::class);
        $this->shouldImplement(Loader::class);
    }

    public function it_loads_from_container_adapter($container, $something)
    {
        $container->has('key')->shouldBeCalled()->willReturn(true);

        $container->get('key')->shouldBeCalled()->willReturn($something);
        $this->get('key')->shouldBe($something);
    }

    public function it_throws_exceptions_if_can_not_load_key($container)
    {
        $container->has('key')->shouldBeCalled()->willReturn(false);
        $this->shouldThrow(InvalidLoaderKey::class)->during('get', ['key']);
    }
}
