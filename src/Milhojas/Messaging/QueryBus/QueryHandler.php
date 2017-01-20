<?php

namespace Milhojas\Messaging\QueryBus;

/**
 * A QueryHandler answer Queries passed to it
 * It should be constructed with all collaborator needed to compute the answer.
 */
interface QueryHandler
{
    /**
     * @param Query $query to answer
     *
     * @return mixed the answer to the query
     */
    public function answer(Query $query);
}
