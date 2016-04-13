<?php

namespace SZG\FeedBundle\Feed\ElasticSearch;

use Elastica\Filter;
use Elastica\Query;
use SZG\FeedBundle\Feed\ElasticSearch\Abstracts\RandomAbstract;

/**
 * Class Random
 * Alias: random
 *
 * @package SZG\FeedBundle\Feed\ElasticSearch
 */
class Random extends RandomAbstract
{

    /**
     * @param Query      $query
     * @param Query\Bool $elasticaQueryBool
     *
     * @return Query\Bool
     */
    public function modifyQuery(Query $query, Query\Bool $elasticaQueryBool)
    {
        return $this->setRandomScore($elasticaQueryBool);
    }

}
