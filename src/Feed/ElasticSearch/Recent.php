<?php

namespace SZG\FeedBundle\Feed\ElasticSearch;

use Elastica\Query;
use SZG\FeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

/**
 * Class Recent
 * Alias: recent
 * 
 * @package SZG\FeedBundle\Feed\ElasticSearch
 */
class Recent implements FeedElasticSearchInterface
{

    /**
     * @param Query      $query
     * @param Query\Bool $elasticaQueryBool
     *
     * @return Query\Bool
     */
    function modifyQuery(Query $query, Query\Bool $elasticaQueryBool = null)
    {
        $query->addSort(['created' => ['order' => 'desc']]);

        return $elasticaQueryBool;
    }

}
