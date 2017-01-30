<?php

namespace Milhojas\Messaging\Shared\Loader\Container;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Interop\Container\ContainerInterface;

/**
 * Adapter class to use the Symfony Container as Class Loader for the Messaging system.
 */
class SymfonyContainer implements ContainerInterface
{
    /*
    * Injects an instance of the symfony container (via setContainer).
    *
    * @param ContainerInterface $symfonyContainer Uses the symfony container as loader for classes
    */
    use ContainerAwareTrait;
    /**
     * Loads the class given a string identifier definer in the services.yml configuration.
     *
     * @param string $key a class name or identifier
     *
     * @return object of the desired class
     */
    public function get($key)
    {
        return $this->container->get($key);
    }

    public function has($key)
    {
        return $this->container->has($key);
    }
}
