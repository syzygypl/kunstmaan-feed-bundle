<?php

namespace SZG\FeedBundle\Feed\ElasticSearch;

use Elastica\Filter;
use Elastica\Query;
use SZG\FeedBundle\Feed\ElasticSearch\Abstracts\RandomAbstract;

/**
 * Class ValuableRandom
 * Alias: valuable_random
 *
 * @package SZG\FeedBundle\Feed\ElasticSearch
 */
class ValuableRandom extends RandomAbstract
{

    /**
     * @param Query      $query
     * @param Query\Bool $elasticaQueryBool
     *
     * @return Query\Bool
     */
    public function modifyQuery(Query $query, Query\Bool $elasticaQueryBool)
    {
        $query->setPostFilter(new Filter\Range('average_rating', ['lte' => 5, 'gte' => 4]));

        return $this->setRandomScore($elasticaQueryBool);
    }

}
