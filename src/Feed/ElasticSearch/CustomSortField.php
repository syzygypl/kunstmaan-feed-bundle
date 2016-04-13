<?php

namespace SZG\FeedBundle\Feed\ElasticSearch;

use Elastica\Query;
use SZG\FeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

/**
 * Class CustomSortField
 * @package SZG\FeedBundle\Feed\ElasticSearch
 *
 * @example You have to define custom feed service:
 *    szg_feed.feed.custom_sort_field_video:
 *       class: "%szg_feed.feed.custom_sort_field_class%"
 *        arguments: ["video", "desc"]
 *       tags:
 *           - { name: szg_feed.feed, alias: video }
 */
class CustomSortField implements FeedElasticSearchInterface
{

    const NAME = 'customSortField';

    /** @var string */
    private $field;

    /** @var string 'desc' or 'asc' */
    private $order;

    public function __construct($field, $order = 'desc')
    {
        $this->setField($field);
        $this->setOrder($order);
    }

    /**
     * @param Query      $query
     * @param Query\Bool $elasticaQueryBool
     *
     * @return Query\Bool|void
     */
    function modifyQuery(Query $query, Query\Bool $elasticaQueryBool = null)
    {
        $query->addSort([$this->field => ['order' => $this->order]]);

        return $elasticaQueryBool;
    }

    /**
     * @return string
     */
    function getName()
    {
        self::NAME;
    }

    /**
     * @param string $order
     */
    private function setOrder($order)
    {
        if (!in_array($order, ['asc', 'desc'])) {
            throw new \InvalidArgumentException('Method setOrder expected asc or desc value.');
        }

        $this->order = $order;
    }

    /**
     * @param string $field
     */
    private function setField($field)
    {
        $this->field = $field;
    }

}
