<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain;

use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain\Exception\FeedDoesNotExistException;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

class ElasticSearchFeedChain extends \RecursiveArrayIterator
{

    /** @var FeedElasticSearchInterface[] */
    private $children;

    public function __construct()
    {
        $this->children = [];
    }

    /**
     * @param FeedElasticSearchInterface $feed
     * @param string $alias
     */
    public function addFeed(FeedElasticSearchInterface $feed, $alias)
    {
        $this->children[$alias] = $feed;
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

        return $this->children[$alias];

    }

    /**
     * @param $alias
     * @return bool
     */
    public function exist($alias)
    {
        return array_key_exists($alias, $this->children);
    }

}
