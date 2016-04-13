<?php

namespace SZG\FeedBundle\Feed\ElasticSearch\Chain;

use SZG\FeedBundle\Feed\ElasticSearch\Chain\Exception\FeedDoesNotExistException;
use SZG\FeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

class ElasticSearchFeedChain
{

    /** @var FeedElasticSearchInterface[] */
    private $feeds;

    public function __construct()
    {
        $this->feeds = [];
    }

    /**
     * @param FeedElasticSearchInterface $feed
     * @param string                     $alias
     */
    public function addFeed(FeedElasticSearchInterface $feed, $alias)
    {
        $this->feeds[$alias] = $feed;
    }

    /**
     * @param string $alias
     *
     * @return FeedElasticSearchInterface
     * @throws FeedDoesNotExistException
     */
    public function getFeed($alias)
    {
        if (array_key_exists($alias, $this->feeds)) {
            return $this->feeds[$alias];
        }

        throw new FeedDoesNotExistException($alias);
    }

}
