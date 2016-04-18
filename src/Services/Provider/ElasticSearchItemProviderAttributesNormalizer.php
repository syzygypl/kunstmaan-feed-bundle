<?php

namespace SZG\KunstmaanFeedBundle\Services\Provider;

use ArsThanea\KunstmaanExtraBundle\ContentCategory\ContentCategoryInterface;
use Entity\Category;
use Kunstmaan\NodeBundle\Entity\HasNodeInterface;
use Kunstmaan\TaggingBundle\Entity\Tag;
use Kunstmaan\TaggingBundle\Entity\Taggable;
use Symfony\Component\OptionsResolver\Options;
use SZG\KunstmaanFeedBundle\DTO\TagLogic;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain\ElasticSearchFeedChain;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain\Exception\FeedDoesNotExistException;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

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

    /**
     * @param ContentCategoryInterface $contentCategory
     * @param ElasticSearchFeedChain $feedChain
     */
    public function __construct(ContentCategoryInterface $contentCategory, ElasticSearchFeedChain $feedChain)
    {
        $this->contentCategory = $contentCategory;
        $this->feedChain = $feedChain;
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
     * @return Category|null
     */
    public function normalizeCategory(Options $options, $value)
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof Category) {
            return $value;
        }

        if ($value instanceof HasNodeInterface) {
            return $this->contentCategory->getCurrentCategory($value);
        }

        return null;
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

}