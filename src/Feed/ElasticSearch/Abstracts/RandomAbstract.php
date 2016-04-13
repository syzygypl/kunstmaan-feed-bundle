<?php

namespace SZG\FeedBundle\Feed\ElasticSearch\Abstracts;

use Elastica\Filter;
use Elastica\Query;
use SZG\FeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

abstract class RandomAbstract implements FeedElasticSearchInterface
{

    /**
     * @param Query\AbstractQuery $elasticaQuery
     *
     * @return Query\AbstractQuery
     */
    protected function setRandomScore(Query\AbstractQuery $elasticaQuery)
    {
        $seed = round(microtime(true) * 1000) % 10000;

        return (new Query\FunctionScore())->setQuery($elasticaQuery)->setRandomScore($seed);
    }

}
