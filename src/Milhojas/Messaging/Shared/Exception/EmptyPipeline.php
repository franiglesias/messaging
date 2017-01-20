<?php

namespace Milhojas\Messaging\Shared\Exception;

/**
 * Try to build a pipeline without MessageWorkers.
 */
class EmptyPipeline extends \LogicException
{
}
