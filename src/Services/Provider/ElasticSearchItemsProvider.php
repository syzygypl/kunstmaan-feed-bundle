<?php

namespace SZG\KunstmaanFeedBundle\Services\Provider;

use Elastica\Result;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SZG\KunstmaanFeedBundle\DTO\RelationDefinition;
use SZG\KunstmaanFeedBundle\DTO\TagLogic;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Recent;
use SZG\KunstmaanFeedBundle\Services\Searcher\ElasticaSearcher;

class ElasticSearchItemsProvider implements ElasticSearchItemsProviderInterface
{

    /** @var ElasticaSearcher */
    private $searcher;

    /** @var ElasticSearchItemProviderAttributesNormalizer */
    private $normalizer;

    /**
     * @param ElasticaSearcher $searcher
     * @param ElasticSearchItemProviderAttributesNormalizerInterface $normalizer
     */
    public function __construct(ElasticaSearcher $searcher, ElasticSearchItemProviderAttributesNormalizerInterface $normalizer)
    {
        $this->searcher = $searcher;
        $this->normalizer = $normalizer;
    }

    /**
     * @param string $contentType
     *
     * @param array $options
     * @param bool $returnDocuments
     * @return \Elastica\Result[]
     */
    public function getFeedItems($contentType, array $options = [], $returnDocuments = false)
    {
        $options = $this->resolveGetItemsOptions($options);
        $relation = new RelationDefinition($options['category'], $options['tags'], $options['excluded'], $options['extra']);
        $offset = ($options['page'] - 1) * $options['limit'];
        $resultSet = $this->searcher
            ->setFeed($options['feed'])
            ->setTagsLogic($options['tagsLogic'])
            ->setData($relation)
            ->setContentType($contentType)
            ->search($offset, $options['limit']);

        $results = $resultSet->getResults();

        if (true === $returnDocuments) {
            $results = array_map(function (Result $result) {
                return $result->getParam('_source');
            }, $results);
        }

        return $results;
    }

    /**
     * @param $options
     * @return array
     */
    private function resolveGetItemsOptions($options)
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'limit' => 100,
            'feed' => new Recent(),
            'excluded' => [],
            'page' => 1,
            'category' => null,
            'tags' => null,
            'tagsLogic' => new TagLogic(TagLogic::LOGIC_ANY),
            'extra' => null
        ]);

        $resolver->setNormalizer('feed', function (Options $options, $value) {
            return $this->normalizer->normalizeFeed($options, $value);
        });

        $resolver->setNormalizer('category', function (Options $options, $value) {
            return $this->normalizer->normalizeCategory($options, $value);
        });

        $resolver->setNormalizer('tags', function (Options $options, $value) {
            return $this->normalizer->normalizeTags($options, $value);
        });

        $resolver->setNormalizer('tagsLogic', function (Options $options, $value) {
            return $this->normalizer->normalizeTagLogic($options, $value);
        });

        $resolver->setNormalizer('excluded', function (Options $options, $value) {
            return $this->normalizer->normalizeExcluded($options, $value);
        });

        return $resolver->resolve($options);
    }

}
