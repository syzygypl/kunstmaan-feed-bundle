<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch;

use SZG\KunstmaanFeedBundle\DTO\RelationDefinition;
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
     * @param RelationDefinition $relationDefinition
     */
    public function modifyQuery(QueryDefinition $queryDefinition, RelationDefinition $relationDefinition)
    {
        $queryDefinition->setFilterQuery($this->setRandomScore($queryDefinition->getFilterQuery()));
    }

}
