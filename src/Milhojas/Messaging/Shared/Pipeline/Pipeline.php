<?php

namespace Milhojas\Messaging\Shared\Pipeline;

use Milhojas\Messaging\Shared\Worker\Worker;

/**
 * Acts as Worker Composite, so Pipeline and Workers are equivalent.
 */
interface Pipeline extends Worker
{
}
