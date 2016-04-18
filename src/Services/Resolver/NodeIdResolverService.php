<?php

namespace SZG\KunstmaanFeedBundle\Services\Resolver;

use ArsThanea\KunstmaanExtraBundle\ContentCategory\Category;
use ArsThanea\KunstmaanExtraBundle\SiteTree\PublicNodeVersions;
use Elastica\Result;
use Kunstmaan\NodeBundle\Entity\HasNodeInterface;
use Kunstmaan\NodeBundle\Entity\Node;
use Kunstmaan\NodeBundle\Entity\NodeTranslation;

class NodeIdResolverService implements NodeIdResolverServiceInterface
{

    /**
     * @var PublicNodeVersions
     */
    private $nodeVersions;

    /**
     * @param PublicNodeVersions $nodeVersions
     */
    public function __construct(PublicNodeVersions $nodeVersions)
    {
        $this->nodeVersions = $nodeVersions;
    }

    /**
     * @param mixed $value
     * @return int
     */
    public function resolve($value)
    {
        if (is_int($value) || is_string($value)) {
            return (int)$value;
        }

        if ($value instanceof Category) {
            return $value->getNodeId();
        }

        if ($value instanceof Result) {
            return $value->get('node_id');
        }

        if ($value instanceof Node) {
            return $value->getId();
        }

        if ($value instanceof NodeTranslation) {
            return $value->getNode()->getId();
        }

        if ($value instanceof HasNodeInterface) {
            return $this->nodeVersions->getNodeFor($value)->getId();
        }

        throw new \InvalidArgumentException();
    }

}
