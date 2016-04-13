<?php

namespace SZG\Services\Twig;

use SZG\FeedBundle\Services\Provider\ElasticSearchItemsProvider;
use SZG\FeedBundle\Services\Searcher\RelationDefinition;

class FeedExtension extends \Twig_Extension
{

    /**
     * @var ElasticSearchItemsProvider
     */
    private $contentProvider;

    /**
     * @param ElasticSearchItemsProvider $contentProvider
     */
    public function __construct(ElasticSearchItemsProvider $contentProvider)
    {
        $this->contentProvider = $contentProvider;
    }


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_items', [$this, 'getItems']),
        ];
    }

    /**
     * @param string $contentType
     * @param string $feedType @see config/feeds.yml
     * @param int $limit
     * @param RelationDefinition|null $relation
     *
     * @example Get ten most recent articles:    get_items('articles', 'most_recent', 10)
     * @example Get random product:              get_items('product', 'random', 1)
     *
     * @return \Elastica\Result[]
     */
    public function getItems($contentType, $feedType, $limit = 3, RelationDefinition $relation = null)
    {
        return $this->contentProvider->getItems($contentType, $feedType, $limit, $relation);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'szg_feed_twig_extension';
    }
}
