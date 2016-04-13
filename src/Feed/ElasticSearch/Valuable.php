<?php

namespace SZG\FeedBundle\Feed\ElasticSearch;

use Elastica\Query;
use SZG\FeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

/**
 * Class Valuable
 * Alias: valuable
 * 
 * @package SZG\FeedBundle\Feed\ElasticSearch
 */
class Valuable implements FeedElasticSearchInterface
{

    /**
     * @param Query      $query
     * @param Query\Bool $elasticaQueryBool
     *
     * @return Query\Bool
     */
    function modifyQuery(Query $query, Query\Bool $elasticaQueryBool = null)
    {
        $query->addSort(['average_rating' => ['order' => 'desc']]);

        return $elasticaQueryBool;
    }


}
