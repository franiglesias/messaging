<?php

namespace Milhojas\Messaging\Shared\Inflector;

/**
 * Description.
 */
class HandlerInflector implements Inflector
{
    public function inflect($message)
    {
        $parts = explode('\\', $message);
        $class = end($parts);
        $handler = preg_replace(array('/Command$/', '/(?<=.)[A-Z]/'), array('', '_$0'), $class).'_handler';

        return strtolower($handler);
    }
}
