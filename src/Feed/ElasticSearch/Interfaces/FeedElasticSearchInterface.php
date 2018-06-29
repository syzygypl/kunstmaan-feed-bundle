<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces;

use SZG\KunstmaanFeedBundle\DTO\QueryDefinition;
use SZG\KunstmaanFeedBundle\DTO\RelationDefinition;

interface FeedElasticSearchInterface
{
    /**
     * @param QueryDefinition $queryDefinition
     * @param RelationDefinition $relationDefinition
     * @return void
     */
    function modifyQuery(QueryDefinition $queryDefinition, RelationDefinition $relationDefinition);

}
