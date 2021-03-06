<?php

namespace SZG\KunstmaanFeedBundle\Services\Provider;

use ArsThanea\KunstmaanExtraBundle\ContentCategory\ContentCategoryInterface;
use Kunstmaan\TaggingBundle\Entity\Tag;
use Kunstmaan\TaggingBundle\Entity\Taggable;
use Symfony\Component\OptionsResolver\Options;
use SZG\KunstmaanFeedBundle\DTO\TagLogic;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain\ElasticSearchFeedChain;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain\Exception\FeedDoesNotExistException;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;
use SZG\KunstmaanFeedBundle\Services\Resolver\NodeIdResolverServiceInterface;

/**
 * Class ElasticSearchItemProviderAttributesNormalizer
 * @package SZG\KunstmaanFeedBundle\Services\Provider
 */
class ElasticSearchItemProviderAttributesNormalizer implements ElasticSearchItemProviderAttributesNormalizerInterface
{

    /** @var ContentCategoryInterface */
    private $contentCategory;

    /**  @var ElasticSearchFeedChain */
    private $feedChain;

    /**  @var NodeIdResolverServiceInterface */
    private $nodeIdResolver;

    /**
     * @param ContentCategoryInterface $contentCategory
     * @param ElasticSearchFeedChain $feedChain
     * @param NodeIdResolverServiceInterface $nodeIdResolver
     */
    public function __construct(ContentCategoryInterface $contentCategory, ElasticSearchFeedChain $feedChain, NodeIdResolverServiceInterface $nodeIdResolver)
    {
        $this->contentCategory = $contentCategory;
        $this->feedChain = $feedChain;
        $this->nodeIdResolver = $nodeIdResolver;
    }

    /**
     * @param Options $options
     * @param $value
     * @return TagLogic
     */
    public function normalizeTagLogic(Options $options, $value)
    {
        if ($value instanceof TagLogic) {
            return $value;
        }

        return new TagLogic($value);
    }

    /**
     * @param Options $options
     * @param mixed $value
     * @return int
     */
    public function normalizeCategory(Options $options, $value)
    {
        if (null === $value) {
            return null;
        }

        return $this->nodeIdResolver->resolve($value);
    }

    /**
     * @param Options $options
     * @param mixed $value
     * @return array|null
     */
    public function normalizeTags(Options $options, $value)
    {
        if (null === $value) {

            return null;
        }

        if (is_string($value)) {

            return [$value];
        }

        if (is_array($value)) {

            return $value;
        }

        if ($value instanceof Taggable) {
            $tags = [];
            /** @var Tag $tag */
            foreach ($value->getTags() as $tag) {
                $tags[] = $tag->getName();
            }

            return $tags;
        }

        return null;
    }

    /**
     * @param Options $options
     * @param FeedElasticSearchInterface|string $value
     * @return FeedElasticSearchInterface
     * @throws FeedDoesNotExistException
     */
    public function normalizeFeed(Options $options, $value)
    {
        return $value instanceof FeedElasticSearchInterface ? $value : $this->feedChain->getFeed($value);
    }

    /**
     * @param Options $options
     * @param mixed $value
     *
     * @return array
     */
    public function normalizeExcluded(Options $options, $value)
    {
        $value = (array)$value;
        if (empty($value)) {
            return [];
        }

        $items = [];
        foreach ($value as $item) {
            $items[] = $this->nodeIdResolver->resolve($item);
        }

        return $items;
    }

}
