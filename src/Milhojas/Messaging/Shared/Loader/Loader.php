<?php

namespace Milhojas\Messaging\Shared\Loader;

/**
 * Adapter interface to load handler classes.
 */
interface Loader
{
    /**
     * @param string $className identifier for the class to load. It could be the class name or the label ina container
     */
    public function get($className);
}
