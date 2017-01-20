<?php

namespace Milhojas\Messaging\Shared\Loader;

use Milhojas\Messaging\Shared\Exception\InvalidLoaderKey;

class TestLoader implements Loader
{
    private $handlers;

    public function add($key, $handler)
    {
        $this->handlers[$key] = $handler;
    }

    public function get($key)
    {
        if (!isset($this->handlers[$key])) {
            throw new InvalidLoaderKey(sprintf('%s is an unknown key.', $key), 1);
        }

        return $this->handlers[$key];
    }
}
