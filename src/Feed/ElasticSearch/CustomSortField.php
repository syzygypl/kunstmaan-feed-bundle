<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch;

use Elastica\Query;
use SZG\KunstmaanFeedBundle\DTO\QueryDefinition;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

/**
 * Class CustomSortField
 * @package SZG\KunstmaanFeedBundle\Feed\ElasticSearch
 *
 * @example
 * Configure custom feeds in config.yml
 *     kunstmaan_feed:
 *          custom:
 *              title : 'asc'
 *              rate_avg : 'desc'
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
     * @param QueryDefinition $queryDefinition
     */
    public function modifyQuery(QueryDefinition $queryDefinition)
    {
        $queryDefinition->getQuery()->addSort([$this->field => ['order' => $this->order]]);
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
