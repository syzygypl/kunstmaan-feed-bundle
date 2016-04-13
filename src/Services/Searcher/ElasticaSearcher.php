<?php

namespace SZG\FeedBundle\Services\Searcher;

use Elastica\Query;
use Elastica\Query\Bool;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Kunstmaan\NodeSearchBundle\Search\AbstractElasticaSearcher;
use SZG\FeedBundle\Feed\ElasticSearch\Chain\ElasticSearchFeedChain;
use SZG\FeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;
use SZG\FeedBundle\Services\RelationDefinition;

class ElasticaSearcher extends AbstractElasticaSearcher
{

    /** @var ElasticSearchFeedChain */
    private $feedChain;

    /**
     * @var FeedElasticSearchInterface
     */
    private $feed;

    /**
     * @param $feed
     *
     * @return $this
     * @throws \SZG\FeedBundle\Feed\ElasticSearch\Chain\Exception\FeedDoesNotExistException
     */
    public function setFeed($feed)
    {
        if (is_string($feed)) {
            $this->feed = $this->feedChain->getFeed($feed);
        } elseif ($feed instanceof FeedElasticSearchInterface) {
            $this->feed = $feed;
        }

        return $this;
    }

    /**
     * @param RelationDefinition $query
     * @param string             $lang
     * @param string             $type
     *
     * @return void
     */
    public function defineSearch($query, $lang, $type)
    {
        $category = $query->getCategory();
        $tags = $query->getTags();
        $exclude = $query->getExclude();

        $elasticaQueryBool = (new Bool)->setMinimumNumberShouldMatch(1);

        $elasticaQueryBool->addMust((new Term)->setTerm('lang', $lang));
        $elasticaQueryBool->addMust((new Term)->setTerm('type', $type));
        $elasticaQueryBool->addMust((new Term)->setTerm('view_roles', 'IS_AUTHENTICATED_ANONYMOUSLY'));

        // check if slug is non-empty: avoid filtering for home page
        if ($category && $category->getSlug()) {
            $elasticaQueryBool->addShould((new Term)->setTerm('ancestors', $category->getNodeId()));
        }

        if ($tags) {
            $minimum = max(1, ceil(sizeof($tags) / 3));
            $elasticaQueryBool->addShould((new Terms)->setMinimumMatch($minimum)->setTerms('tags', $tags));
        }

        if (0 !== sizeof($exclude)) {
            $elasticaQueryBool->addMustNot((new Terms)->setTerms('node_id', $exclude));
        }

        $this->query = new Query();
        $this->query->setQuery($this->feed->modifyQuery($this->query, $elasticaQueryBool));

    }

}
