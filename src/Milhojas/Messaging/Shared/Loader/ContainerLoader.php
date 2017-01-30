<?php

namespace Milhojas\Messaging\Shared\Loader;

use Interop\Container\ContainerInterface;
use Milhojas\Messaging\Shared\Exception\InvalidLoaderKey;

/**
 * Container Loader uses a DI Container to load classes given a key.
 * An instance of a DIC must be injected on instantiation.
 */
class ContainerLoader implements Loader
{
    /**
     * The ContainerInterface (Interop\Container\ContainerInterface).
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (!$this->container->has($key)) {
            throw new InvalidLoaderKey(sprintf('Key %s is not registered with container %s.', $key, get_class($this->container)));
        }

        return $this->container->get($key);
    }
}
