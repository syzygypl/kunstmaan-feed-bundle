<?php

namespace SZG\KunstmaanFeedBundle\Services\Twig;

use SZG\KunstmaanFeedBundle\Services\Provider\ElasticSearchItemsProvider;

class FeedExtension extends \Twig_Extension
{

    /**
     * @var ElasticSearchItemsProvider
     */
    private $itemsProvider;

    /**
     * @param ElasticSearchItemsProvider $itemsProvider
     */
    public function __construct(ElasticSearchItemsProvider $itemsProvider)
    {
        $this->itemsProvider = $itemsProvider;
    }


    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_feed_items', [$this, 'getFeedItems']),
            new \Twig_SimpleFunction('get_*_feed', [$this, 'getFeedItemsByType']),
        ];
    }

    /**
     * Get items by content type
     *
     * @param string $contentType
     * @param array $options
     *
     * @example Get 100 recent articles:    get_feed_items('article')
     * @example Get random product:         get_feed_items('product', {'limit':1, 'feed':'random'})
     *      contentType: <search_type>
     *      page:
     *          + 1
     *          - <int>
     *      limit:
     *          + 100
     *          - <int>
     *      category:
     *          + null
     *          - <HasNodeInterface>
     *          - <Category>
     *      tags:
     *          + null
     *          - 'tag1'
     *          - {'tag1', 'tag2'}
     *          - <Taggable>
     *      tags_logic:
     *          + 'any'
     *          - 'all'
     *          - 'few'
     *      exclude:
     *          + null
     *          - {nodeId1, nodeId2, ...}
     *      feed:
     *          + 'recent',
     *          - 'most_rates',
     *          - 'random',
     *          - 'recommended',
     *          - 'valuable',
     *          - 'valuable_random'
     *      returnRawResultSet:
     *          + false
     *      extra:
     *          + null
     *          - mixed
     *
     * @return \Elastica\Result[]
     */
    public function getFeedItems($contentType, $options = [])
    {
        return $this->itemsProvider->getFeedItems($contentType, $options);
    }

    /**
     * @example get_random_news_feed({'limit':1})
     * @example get_article_feed()
     * @param string $type
     * @param array $options
     * @return \Elastica\Result[]
     */
    public function getFeedItemsByType($type, $options = [])
    {
        if (false !== strpos($type, "_")) {
            list($feedType, $contentType) = explode('_', $type);
        } else {
            $feedType = null;
            $contentType = $type;
        }

        $options = array_merge($options, array_filter(['feed' => $feedType]));

        return $this->itemsProvider->getFeedItems($contentType, $options);
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
