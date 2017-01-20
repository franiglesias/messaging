<?php

namespace Milhojas\Messaging\QueryBus\Worker;

use Milhojas\Messaging\QueryBus\Query;
use Milhojas\Messaging\Shared\Message;
use Milhojas\Messaging\Shared\Worker\MessageWorker;
use Milhojas\Messaging\Shared\Loader\Loader;
use Milhojas\Messaging\Shared\Inflector\Inflector;

class QueryWorker extends MessageWorker
{
    /**
     * @var Loader Strategy to load classes
     */
    private $loader;
    /**
     * @var Inflector Strategy to inflect handlers names
     */
    private $inflector;
    /**
     * @param Loader    $loader
     * @param Inflector $inflector
     */
    public function __construct(Loader $loader, Inflector $inflector)
    {
        $this->loader = $loader;
        $this->inflector = $inflector;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Message $query)
    {
        $handler = $this->getHandler($query);

        return $handler->answer($query);
    }

    public function getHandler(Query $query)
    {
        $service = $this->inflector->inflect(get_class($query));

        return $this->loader->get($service);
    }
}
