<?php

namespace SZG\KunstmaanFeedBundle\Services\Searcher;

use ArsThanea\KunstmaanExtraBundle\SiteTree\CurrentLocaleInterface;
use Elastica\Query;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Kunstmaan\NodeSearchBundle\Search\AbstractElasticaSearcher;
use SZG\KunstmaanFeedBundle\DTO\QueryDefinition;
use SZG\KunstmaanFeedBundle\DTO\RelationDefinition;
use SZG\KunstmaanFeedBundle\DTO\TagLogic;
use SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface;

class ElasticaSearcher extends AbstractElasticaSearcher
{

    /**
     * @var CurrentLocaleInterface
     */
    private $currentLocale;

    public function __construct(CurrentLocaleInterface $currentLocale, $indexName, $indexType)
    {
        parent::__construct();
        $this->currentLocale = $currentLocale;
        $this->setIndexName($indexName);
        $this->setIndexType($indexType);
    }

    /**
     * @var FeedElasticSearchInterface
     */
    private $feed;

    /**
     * @var TagLogic
     */
    private $tagsLogic;

    /**
     * @param FeedElasticSearchInterface $feed
     *
     * @return $this
     */
    public function setFeed(FeedElasticSearchInterface $feed)
    {
        $this->feed = $feed;

        return $this;
    }

    /**
     * @param TagLogic $tagsLogic
     * @return $this
     */
    public function setTagsLogic(TagLogic $tagsLogic)
    {
        $this->tagsLogic = $tagsLogic;

        return $this;
    }

    /**
     * @param RelationDefinition $query
     * @param string $lang
     * @param string $type
     *
     * @return void
     */
    public function defineSearch($query, $lang, $type)
    {
        $category = $query->getCategory();
        $tags = $query->getTags();
        $exclude = $query->getExclude();

        $bool = (new Query\BoolQuery())->setMinimumNumberShouldMatch(1);
        $bool->addMust((new Term)->setTerm('lang', $this->language));
        $bool->addMust((new Term)->setTerm('type', $type));
        $bool->addMust((new Term)->setTerm('view_roles', 'IS_AUTHENTICATED_ANONYMOUSLY'));

        // check if slug is non-empty: avoid filtering for home page
        if ($category && $category->getSlug()) {
            $bool->addShould((new Term)->setTerm('ancestors', $category->getNodeId()));
        }

        if ($tags) {
            $tagLogic = $this->tagsLogic instanceof TagLogic ?: new TagLogic(TagLogic::LOGIC_FEW);
            $minimum = $tagLogic->getMinMatch($tags);
            $bool->addShould((new Terms)->setMinimumMatch($minimum)->setTerms('tags', $tags));
        }

        if (0 !== sizeof($exclude)) {
            $bool->addMustNot((new Terms)->setTerms('node_id', $exclude));
        }

        $this->query = new Query();
        $queryDefinition = new QueryDefinition($this->query, $bool);
        $this->feed->modifyQuery($queryDefinition);
        $queryDefinition->getQuery()->setQuery($queryDefinition->getFilterQuery());

    }

    /**
     * @param int|null $offset
     * @param int|null $size
     * @return \Elastica\ResultSet
     */
    public function search($offset = null, $size = null)
    {
        $this->language = $this->currentLocale->getCurrentLocale();
        return parent::search($offset, $size);
    }

}
