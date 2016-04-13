<?php

namespace SZG\FeedBundle\Feed\ElasticSearch;

use Elastica\Query;
use SZG\FeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

/**
 * Class MostRates
 * Alias: most_rated
 * 
 * @package SZG\FeedBundle\Feed\ElasticSearch
 */
class MostRates implements FeedElasticSearchInterface
{

    /**
     * @param Query      $query
     * @param Query\Bool $elasticaQueryBool
     *
     * @return Query\Bool|void
     */
    function modifyQuery(Query $query, Query\Bool $elasticaQueryBool = null)
    {
        $query->addSort(['rating_count' => ['order' => 'desc']]);

        return $elasticaQueryBool;
    }

}
