<?php

namespace SZG\KunstmaanFeedBundle\Services\Searcher;

use ArsThanea\KunstmaanExtraBundle\SiteTree\CurrentLocaleInterface;
use Elastica\Query;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Elastica\ResultSet;
use Kunstmaan\AdminBundle\Helper\DomainConfigurationInterface;
use Kunstmaan\NodeBundle\Entity\Node;
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

    /**
     * @var DomainConfigurationInterface
     */
    private $domainConfiguration;

    /**
     * @param CurrentLocaleInterface $currentLocale
     * @param DomainConfigurationInterface $domainConfiguration
     * @param $indexName
     * @param $indexType
     */
    public function __construct(CurrentLocaleInterface $currentLocale, DomainConfigurationInterface $domainConfiguration, $indexName, $indexType)
    {
        parent::__construct();
        $this->currentLocale = $currentLocale;
        $this->domainConfiguration = $domainConfiguration;

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
     * @param string $type
     *
     * @return void
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function defineSearch($query, $type)
    {
        $category = $query->getCategory();
        $tags = $query->getTags();
        $exclude = $query->getExclude();
        $rootNode = $this->domainConfiguration->getRootNode();

        $bool = (new Query\BoolQuery());
        $bool->addMust((new Term)->setRawTerm(['type' => $type]));
        $bool->addMust((new Term)->setRawTerm(['view_roles' => 'IS_AUTHENTICATED_ANONYMOUSLY']));

        if ($rootNode instanceof Node) {
            $root = new Term();
            $root->setTerm('root_id', $rootNode->getId());
            $bool->addMust($root);
        }

        // check if slug is non-empty: avoid filtering for home page
        if ($category && $category->getSlug()) {
            $bool->addMust((new Term)->setRawTerm(['ancestors' => $category->getNodeId()]));
        }

        if ($tags) {
            $tagLogic = $this->tagsLogic instanceof TagLogic ?: new TagLogic(TagLogic::LOGIC_FEW);
            $minimum = $tagLogic->getMinMatch($tags);
            $bool->addMust((new Terms)->setMinimumMatch($minimum)->setTerms('tags', $tags));
        }

        if (0 !== count($exclude)) {
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
     * @return ResultSet
     */
    public function search($offset = null, $size = null)
    {
        $this->language = $this->currentLocale->getCurrentLocale();
        return parent::search($offset, $size);
    }

}
