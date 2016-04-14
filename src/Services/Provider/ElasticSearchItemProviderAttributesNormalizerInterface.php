<?php

namespace SZG\KunstmaanFeedBundle\Services\Provider;

use Entity\Category;
use Symfony\Component\OptionsResolver\Options;
use SZG\KunstmaanFeedBundle\DTO\TagLogic;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

/**
 * Interface ElasticSearchItemProviderAttributesNormalizerInterface
 * @package SZG\KunstmaanFeedBundle\Services\Provider
 */
interface ElasticSearchItemProviderAttributesNormalizerInterface
{

    /**
     * @param Options $options
     * @param string|TagLogic|null $value
     * @return TagLogic
     */
    function normalizeTagLogic(Options $options, $value);

    /**
     * @param Options $options
     * @param mixed $value
     * @return Category|null
     */
    function normalizeCategory(Options $options, $value);

    /**
     * @param Options $options
     * @param mixed $value
     *
     * @return array|null
     */
    function normalizeTags(Options $options, $value);

    /**
     * @param Options $options
     * @param mixed $value
     *
     * @return FeedElasticSearchInterface
     */
    function normalizeFeed(Options $options, $value);

}
