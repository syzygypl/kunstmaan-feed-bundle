<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch;

use SZG\KunstmaanFeedBundle\DTO\RelationDefinition;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;
use SZG\KunstmaanFeedBundle\DTO\QueryDefinition;

/**
 * Class Recent
 * Alias: recent
 *
 * @package SZG\KunstmaanFeedBundle\Feed\ElasticSearch
 */
class Recent implements FeedElasticSearchInterface
{

    /**
     * @param QueryDefinition $queryDefinition
     * @param RelationDefinition $relationDefinition
     */
    public function modifyQuery(QueryDefinition $queryDefinition, RelationDefinition $relationDefinition)
    {
        $queryDefinition->getQuery()->addSort(['created' => ['order' => 'desc']]);
    }

}
