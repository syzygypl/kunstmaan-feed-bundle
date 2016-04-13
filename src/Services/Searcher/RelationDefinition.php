<?php

namespace SZG\FeedBundle\Services\Searcher;

use ArsThanea\KunstmaanExtraBundle\ContentCategory\Category;

class RelationDefinition implements \JsonSerializable
{
    /**
     * @var Category
     */
    private $category;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var array
     */
    private $exclude = [];

    /**
     * @param Category|null $category
     * @param array $tags
     */
    public function __construct(Category $category = null, array $tags = [])
    {
        $this->category = $category;
        $this->tags = $tags;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $exclude
     * @return $this
     */
    public function setExclude(array $exclude)
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * @return array
     */
    public function getExclude()
    {
        return $this->exclude;
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            'category' => $this->category,
            'tags'     => $this->tags,
            'exclude'  => $this->exclude,
        ];
    }
}
