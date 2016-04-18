<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain;

use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain\Exception\FeedDoesNotExistException;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

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
     * @param string $alias
     */
    public function addFeed(FeedElasticSearchInterface $feed, $alias)
    {
        $this->feeds[$alias] = $feed;
    }

    /**
     * @param string $alias
     * @return FeedElasticSearchInterface
     * @throws FeedDoesNotExistException
     */
    public function getFeed($alias)
    {
        if (!$this->exist($alias)) {
            throw new FeedDoesNotExistException($alias);
        }

        return $this->feeds[$alias];

    }

    /**
     * @param $alias
     * @return bool
     */
    public function exist($alias)
    {
        return array_key_exists($alias, $this->feeds);
    }

}
