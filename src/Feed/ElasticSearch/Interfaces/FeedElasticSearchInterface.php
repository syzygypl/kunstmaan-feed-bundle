<?php

namespace SZG\FeedBundle\Feed\ElasticSearch\Interfaces;

use Elastica\Query;

interface FeedElasticSearchInterface
{
    /**
     * @param Query      $query
     * @param Query\Bool $elasticaQueryBool
     *
     * @return Query\Bool
     */
    function modifyQuery(Query $query, Query\Bool $elasticaQueryBool);

}
