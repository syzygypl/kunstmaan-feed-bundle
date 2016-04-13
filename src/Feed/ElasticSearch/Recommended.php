<?php

namespace SZG\FeedBundle\Feed\ElasticSearch;

use Elastica\Query;
use SZG\FeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

/**
 * Class Recommended
 * Alias: recommended
 * 
 * @package SZG\FeedBundle\Feed\ElasticSearch
 */
class Recommended implements FeedElasticSearchInterface
{

    /**
     * @param Query      $query
     * @param Query\Bool $elasticaQueryBool
     *
     * @return Query\Bool
     */
    function modifyQuery(Query $query, Query\Bool $elasticaQueryBool = null)
    {
        $query->addSort(['nutricia_score' => ['order' => 'desc']]);

        return $elasticaQueryBool;
    }

}