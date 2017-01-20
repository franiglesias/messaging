<?php

namespace Milhojas\Messaging\QueryBus;

use Milhojas\Messaging\Shared\Worker\Worker;

/**
 * It is a mechanism to perform Queries, it returns answers to them.
 */
class QueryBus
{
    private $pipeline;

    public function __construct(Worker $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * Execute command.
     *
     * @param Query $query
     *
     * @author Fran Iglesias
     */
    public function execute(Query $query)
    {
        return $this->pipeline->work($query);
    }
}
