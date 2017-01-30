<?php

namespace Milhojas\Messaging\Shared\Loader;

/**
 * Interface to load handler classes.
 */
interface Loader
{
    /**
     * Loads a class given a key previously registered in a container.
     *
     * @param string $key identifier for the class to load. It could be the class name or the label ina container
     */
    public function get($key);
}
