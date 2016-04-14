<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch;

use Elastica\Query;
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
     */
    public function modifyQuery(QueryDefinition $queryDefinition)
    {
        $queryDefinition->getQuery()->addSort(['created' => ['order' => 'desc']]);
    }

}
