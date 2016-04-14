<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch;

use Elastica\Filter;
use Elastica\Query;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Abstracts\RandomAbstract;
use SZG\KunstmaanFeedBundle\DTO\QueryDefinition;

/**
 * Class Random
 * Alias: random
 *
 * @package SZG\KunstmaanFeedBundle\Feed\ElasticSearch
 */
class Random extends RandomAbstract
{

    /**
     * @param QueryDefinition $queryDefinition
     */
    public function modifyQuery(QueryDefinition $queryDefinition)
    {
        $queryDefinition->setFilterQuery($this->setRandomScore($queryDefinition->getFilterQuery()));
    }

}
