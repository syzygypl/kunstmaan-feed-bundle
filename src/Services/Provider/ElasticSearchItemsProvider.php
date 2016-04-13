<?php

namespace SZG\FeedBundle\Services\Provider;

use SZG\FeedBundle\Services\Searcher\RelationDefinition;
use SZG\FeedBundle\Services\Searcher\ElasticaSearcher;

class ElasticSearchItemsProvider
{
    /**
     * @var ElasticaSearcher
     */
    private $elasticSearchProvider;

    public function __construct(ElasticaSearcher $elasticSearchProvider)
    {
        $this->elasticSearchProvider = $elasticSearchProvider;
    }

    /**
     * @param string $contentType
     * @param string $feedType
     * @param int $limit
     * @param RelationDefinition $relation
     *
     * @return \Elastica\Result[]
     */
    public function getItems($contentType, $feedType, $limit = 3, RelationDefinition $relation = null)
    {
        $resultSet = $this->elasticSearchProvider
            ->setFeed($feedType)
            ->setData($relation)
            ->setContentType($contentType)
            ->search(null, (int)$limit ?: 100);

        return $resultSet->getResults();
    }

}
