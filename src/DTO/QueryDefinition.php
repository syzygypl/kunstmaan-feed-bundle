<?php

namespace SZG\KunstmaanFeedBundle\DTO;

use Elastica\Query;

/**
 * Class QueryDefinition
 * @package SZG\KunstmaanFeedBundle\DTO
 */
class QueryDefinition
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var Query\AbstractQuery
     */
    private $filterQuery;

    /**
     * @param Query $query
     * @param Query\AbstractQuery $filterQuery
     */
    public function __construct(Query $query, Query\AbstractQuery $filterQuery)
    {
        $this->query = $query;
        $this->filterQuery = $filterQuery;
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return Query\AbstractQuery
     */
    public function getFilterQuery()
    {
        return $this->filterQuery;
    }

    /**
     * @param Query $query
     */
    public function setQuery(Query $query = null)
    {
        $this->query = $query;
    }

    /**
     * @param Query\AbstractQuery $filterQuery
     */
    public function setFilterQuery(Query\AbstractQuery $filterQuery = null)
    {
        $this->filterQuery = $filterQuery;
    }
}
