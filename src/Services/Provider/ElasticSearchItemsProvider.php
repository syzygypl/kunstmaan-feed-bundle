<?php

namespace SZG\KunstmaanFeedBundle\Services\Provider;

use Elastica\Result;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SZG\KunstmaanFeedBundle\DTO\RelationDefinition;
use SZG\KunstmaanFeedBundle\DTO\TagLogic;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain\ElasticSearchFeedChain;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Recent;
use SZG\KunstmaanFeedBundle\Services\Searcher\ElasticaSearcher;

class ElasticSearchItemsProvider
{

    /** @var ElasticaSearcher */
    private $searcher;

    /** @var ElasticSearchItemProviderAttributesNormalizer */
    private $normalizer;

    /** @var ElasticSearchFeedChain */
    private $feedChain;

    /**
     * @param ElasticaSearcher $searcher
     * @param ElasticSearchItemProviderAttributesNormalizer $normalizer
     * @param ElasticSearchFeedChain $feedChain
     */
    public function __construct(ElasticaSearcher $searcher, ElasticSearchItemProviderAttributesNormalizer $normalizer, ElasticSearchFeedChain $feedChain)
    {
        $this->searcher = $searcher;
        $this->normalizer = $normalizer;
        $this->feedChain = $feedChain;
    }

    /**
     * @param string $contentType
     *
     * @param array $options
     * @return \Elastica\Result[]
     */
    public function getFeedItems($contentType, array $options = [], $returnDocuments = true)
    {
        $options = $this->resolveGetItemsOptions($options);
        $relation = new RelationDefinition($options['category'], $options['tags'], $options['excluded']);
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
            'excluded' => null,
            'page' => 1,
            'category' => null,
            'tags' => null,
            'tagsLogic' => new TagLogic(TagLogic::LOGIC_ANY)
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

        return $resolver->resolve($options);
    }

}
