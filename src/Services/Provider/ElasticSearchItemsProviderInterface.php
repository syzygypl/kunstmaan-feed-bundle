<?php

namespace SZG\KunstmaanFeedBundle\Services\Provider;

interface ElasticSearchItemsProviderInterface
{

    /**
     * @param string $contentType
     *
     * @param array $options
     * @param bool $returnDocuments
     * @return \Elastica\Result[]|array
     */
    public function getFeedItems($contentType, array $options = [], $returnDocuments = false);
}
