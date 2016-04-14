<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces;

use Elastica\Query;
use SZG\KunstmaanFeedBundle\DTO\QueryDefinition;

interface FeedElasticSearchInterface
{
    /**
     * @param QueryDefinition $queryDefinition
     * @return void
     */
    function modifyQuery(QueryDefinition $queryDefinition);

}
