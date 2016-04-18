<?php

namespace SZG\KunstmaanFeedBundle\DTO;

use ArsThanea\KunstmaanExtraBundle\ContentCategory\Category;

/**
 * Class RelationDefinition
 * @package SZG\KunstmaanFeedBundle\DTO
 */
class RelationDefinition implements \JsonSerializable
{
    /**
     * @var Category
     */
    private $category;

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var array
     */
    private $exclude = [];

    /**
     * @param Category|null $category
     * @param array $tags
     * @param array $exclude
     */
    public function __construct(Category $category = null, array $tags = null, array $exclude = null)
    {
        $this->category = $category;
        $this->tags = $tags;
        $this->exclude = $exclude;
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
            'tags' => $this->tags,
            'exclude' => $this->exclude,
        ];
    }
}