<?php

namespace Milhojas\Messaging\Shared\Inflector;

/**
 * Interface for different Inflector strategies.
 */
interface Inflector
{
    /**
     * @param string $className Name of the class to inflect
     */
    public function inflect($className);
}
